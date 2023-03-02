describe('money out summary', () => {

    beforeEach(() => {

        const generateEmail = require('random-email');
        const email = generateEmail({domain: 'example.com'});
        const password = '1234567';
        const deputyFirstName = 'James';
        const deputyLastName = 'Jones';
        const clientFirstName = 'Matthew';
        const clientLastName = 'Peters';
        const caseNumber = '10010010';

        cy.visit('http://localhost:8000/register')
        cy.get('[id=registration_form_deputyFirstName]').type(deputyFirstName)
        cy.get('[id=registration_form_deputyLastName]').type(deputyLastName)
        cy.get('[id=registration_form_email]').type(email).then(response => ({...email}))
        cy.get('[id=registration_form_clientsFirstNames]').type(clientFirstName)
        cy.get('[id=registration_form_clientsLastName]').type(clientLastName)
        cy.get('[id=registration_form_clientsCaseNumber]').type(caseNumber)
        cy.get('[id=registration_form_plainPassword]').type(password).then(response => ({...password}))
        cy.get('#registerButton').click()
        cy.intercept('POST', '/api/user/').as('waiting')

        cy.url().should('eq', 'http://localhost:8000/money_out')

        const filepath = './cypress/downloads/money_out_template.xlsx';
        cy.get('input[type=file]').selectFile(filepath)
        cy.get('#upload').click()
        cy.get('#flashMessage').should('be.visible')
        cy.get('#saveAndContinue').click()
        cy.url().should('eq', 'http://localhost:8000/money_out_summary')

    })



    it('table headings are correctly displayed to the user', () => {
        const headings = ['Type', 'Description', 'Type of bank account','Amount']
        cy.get('table thead th').then(($td) => {
            const texts = Cypress._.map($td, 'innerText')
            expect(texts, 'headings').to.deep.equal(headings)
        })
    });


    it('user uploads money out file and successfully views line by line items', () => {

           cy.get('td:nth-child(1)').each(($el, index, $list) => {
                var text= $el.text();

                if(text.includes('Clothes')) {
                    cy.get('td:nth-child(4)').eq(index).then(function (amount) {
                        var actualAmount = amount.text();
                        expect(actualAmount).to.equal('£50.00');
                    })
                }

                if(text.includes('Broadband')){
                    cy.get('td:nth-child(4)').eq(index).then(function(amount){
                        var actualAmount = amount.text();
                        expect(actualAmount).to.equal('£100.00');
                    })
                }

               if(text.includes('Rent')){
                   cy.get('td:nth-child(4)').eq(index).then(function(amount){
                       var actualAmount = amount.text();
                       expect(actualAmount).to.equal('£20,000.00');
                   })
               }

               if(text.includes('Medical Expenses')){
                   cy.get('td:nth-child(4)').eq(index).then(function(amount){
                       var actualAmount = amount.text();
                       expect(actualAmount).to.equal('£1,700.00');
                   })
               }
        });
    });

    it('confirms the table does not contain item that was not submitted', () => {
        cy.get("tr td:nth-child(1)").eq(1)
            .contains("Food shopping")
            .should('not.exist')
    })


    it('confirms graphs are visible to the user when they click onto the next page', () => {
        cy.get('#spendingSummaryButton').click()
        cy.url().should('eq', 'http://localhost:8000/graphSummary')
        cy.get('#pie_chart_div').should('exist');
        cy.get('#bar_chart_div').should('exist');
    })

});
