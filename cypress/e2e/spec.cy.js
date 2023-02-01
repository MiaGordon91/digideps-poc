describe('registration page', () => {

  beforeEach(() => {
    cy.visit('http://localhost:8000/register')
  })

    const loginEmail = require('random-email');
    const password = '1234567';

  it('user successfully registers ', () =>
  {

    cy.get('[id=registration_form_email]').type(loginEmail({loginEmail: 'example.com'}))
    cy.get('[id=registration_form_plainPassword]').type(password)
    cy.get('[type="checkbox"]').check()

    cy.get('#registerButton').click()

    //user should be redirected to /dashboard
    cy.url().should('include', '/dashboard')
  });


  it('user cant register with empty fields', () =>
  {

    cy.get('[id=registration_form_email]').should('not.have.value')
    cy.get('[id=registration_form_plainPassword]').should('not.have.value')
    cy.get('[type="checkbox"]').check()

    cy.get('#registerButton').click()

    //user should see prompt to fill in field
    cy.on('window:alert', (str) => {
        expect(str).to.equal(`Please fill in this field`)
    })
  });
});


describe('login page', () => {

  const loginEmail = require('random-email');
  const updatedLoginEmail = loginEmail({domain: 'example.com'});
  const password = '1234567';

  beforeEach(() => {

    cy.visit('http://localhost:8000/register')
    cy.get('[id=registration_form_email]').type(updatedLoginEmail)
    cy.get('[id=registration_form_plainPassword]').type(password)
    cy.get('[type="checkbox"]').check()

    cy.get('#registerButton').click()

  })

    it('user successfully logs in with same registration details', () =>
    {
      cy.visit('http://localhost:8000/login')
      cy.get('[id=inputEmail]').type(updatedLoginEmail)
      cy.get('[id=inputPassword]').type(password)

      cy.get('#signIn').click()

      //user should be redirected to /dashboard
      cy.url().should('include', '/dashboard')
      cy.url().should('eq', 'http://localhost:8000/dashboard')
  });

  it('should error if user enters incorrect password', () =>
  {
    cy.visit('http://localhost:8000/login')
    cy.get('[id=inputEmail]').type(updatedLoginEmail)
    cy.get('[id=inputPassword]').type('12345')
    cy.get('#signIn').click()
    cy.get('#logInError').should('be.visible')

  });
});
