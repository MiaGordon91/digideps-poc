describe('money out download', () => {

    beforeEach(() => {
        cy.visit('http://localhost:8000/register')
    })

    const generateEmail = require('random-email');
    const password = '1234567';

    it('user successfully downloads money out file', () => {

        cy.get('[id=registration_form_email]').type(generateEmail({domain: 'example.com'}))
        cy.get('[id=registration_form_plainPassword]').type(password)

        cy.get('#registerButton').click()

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
