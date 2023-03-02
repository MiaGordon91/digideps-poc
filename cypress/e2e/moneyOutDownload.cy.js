describe('money out download', () => {

    beforeEach(() => {
        cy.visit('http://localhost:8000/register')
    })

    const deputyFirstName = 'James';
    const deputyLastName = 'Jones';
    const clientFirstName = 'Matthew';
    const clientLastName = 'Peters';
    const caseNumber = '10010010';

    const generateEmail = require('random-email');
    const password = '1234567';

    it('user successfully downloads money out file', () => {

        cy.get('[id=registration_form_deputyFirstName]').type(deputyFirstName)
        cy.get('[id=registration_form_deputyLastName]').type(deputyLastName)
        cy.get('[id=registration_form_email]').type(generateEmail({domain: 'example.com'}))
        cy.get('[id=registration_form_clientsFirstNames]').type(clientFirstName)
        cy.get('[id=registration_form_clientsLastName]').type(clientLastName)
        cy.get('[id=registration_form_clientsCaseNumber]').type(caseNumber)
        cy.get('[id=registration_form_plainPassword]').type(password).then(response => ({...password}))

        cy.get('#registerButton').click()
        cy.intercept('POST', '/api/user/').as('waiting')

        //user should be redirected to /money_out
        cy.url().should('eq', 'http://localhost:8000/money_out')

        //Event listener added for 'click' which fires a page reload and triggers page load event
        // to avoid getting page load timeout and allows future cypress commands to work
        cy.window().document().then(function (doc) {
            doc.addEventListener('click', () => {
                setTimeout(function () { doc.location.reload() }, 5000)
            })

            cy.get('#downloadMoneyOut').click()
            cy.verifyDownload('money_out_template.xlsx');
     });
   });
});
