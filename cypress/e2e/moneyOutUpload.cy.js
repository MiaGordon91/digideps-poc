describe('money out upload', () => {

    const generateEmail = require('random-email');
    const password = '1234567';
    const deputyFirstName = 'James';
    const deputyLastName = 'Jones';
    const clientFirstName = 'Matthew';
    const clientLastName = 'Peters';
    const caseNumber = '10010010';

    beforeEach(() => {
        cy.visit('http://localhost:8000/register')
        cy.get('[id=registration_form_deputyFirstName]').type(deputyFirstName)
        cy.get('[id=registration_form_deputyLastName]').type(deputyLastName)
        cy.get('[id=registration_form_email]').type(generateEmail({domain: 'example.com'}))
        cy.get('[id=registration_form_clientsFirstNames]').type(clientFirstName)
        cy.get('[id=registration_form_clientsLastName]').type(clientLastName)
        cy.get('[id=registration_form_clientsCaseNumber]').type(caseNumber)
        cy.get('[id=registration_form_plainPassword]').type(password).then(response => ({...password}))

        cy.get('#registerButton').click()
    })



    it('user successfully uploads money out file', () => {
        cy.url().should('eq', 'http://localhost:8000/money_out')

        //Event listener added for 'click' which fires a page reload and triggers page load event
        // to avoid getting page load timeout and allows future cypress commands to work
        cy.window().document().then(function (doc) {
            doc.addEventListener('click', () => {
                setTimeout(function () { doc.location.reload() }, 5000)
            })

            cy.get('#downloadMoneyOut').click()
            cy.verifyDownload('money_out_template.xlsx');

            const filepath = './cypress/uploads/money_out_template.xlsx';
            cy.get('input[type=file]').selectFile(filepath)
            cy.get('#upload').click()
            cy.get('#flashMessage').should('be.visible')
        });
    });
});
