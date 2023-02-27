describe('money out summary', () => {

    beforeEach(() => {

        const generateEmail = require('random-email');
        const password = '1234567';

        cy.visit('http://localhost:8000/register')

        cy.get('[id=registration_form_email]').type(generateEmail({domain: 'example.com'}))
        cy.get('[id=registration_form_plainPassword]').type(password)
        cy.get('#registerButton').click()
        cy.url().should('eq', 'http://localhost:8000/money_out')

        const filepath = './cypress/downloads/money_out_template.xlsx';
        cy.get('input[type=file]').selectFile(filepath)
        cy.get('#upload').click()
        cy.get('#flashMessage').should('be.visible')
        cy.get('#saveAndContinue').click()
        cy.url().should('eq', 'http://localhost:8000/money_out_summary')

    })



    it('user uploads money out file and views line by line items', () => {

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
});
