describe('registration page', () => {

  beforeEach(() => {
    cy.visit('http://localhost:8000/register')
  })

    const generateEmail = require('random-email');
    const password = '1234567';
    const deputyFirstName = 'James';
    const deputyLastName = 'Jones';
    const clientFirstName = 'Matthew';
    const clientLastName = 'Peters';
    const caseNumber = '10010010';

  it('user successfully registers ', () => {

    cy.get('[id=registration_form_deputyFirstName]').type(deputyFirstName)
    cy.get('[id=registration_form_deputyLastName]').type(deputyLastName)
    cy.get('[id=registration_form_email]').type(generateEmail({domain: 'example.com'}))
    cy.get('[id=registration_form_clientsFirstNames]').type(clientFirstName)
    cy.get('[id=registration_form_clientsLastName]').type(clientLastName)
    cy.get('[id=registration_form_clientsCaseNumber]').type(caseNumber)
    cy.get('[id=registration_form_plainPassword]').type(password)

    cy.get('[id=registerButton]').click()

    //user should be redirected to /money_out
    cy.url().should('eq', 'http://localhost:8000/money_out')
  });


  it('user cant register with empty fields', () =>
  {
    cy.get('[id=registration_form_email]').should('not.have.value')
    cy.get('[id=registration_form_plainPassword]').should('not.have.value')

    cy.get('#registerButton').click()

    //user should see prompt to fill in field
    cy.on('window:alert', (str) => {
        expect(str).to.equal(`Please fill in this field`)
    })
  });
});


describe('login page', () => {

    const generateEmail = require('random-email');
    const email = generateEmail({domain: 'example.com'});
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
      cy.get('[id=registration_form_email]').type(email).then(response => ({...email}))
      cy.get('[id=registration_form_clientsFirstNames]').type(clientFirstName)
      cy.get('[id=registration_form_clientsLastName]').type(clientLastName)
      cy.get('[id=registration_form_clientsCaseNumber]').type(caseNumber)
      cy.get('[id=registration_form_plainPassword]').type(password).then(response => ({...password}))

      cy.get('#registerButton').click()
      cy.intercept('POST', '/api/user/').as('waiting')
      })


      it('user successfully logs in with same registration details', () =>
      {
        cy.visit('http://localhost:8000/')
        cy.get('[id=inputEmail]').type(email)
        cy.get('[id=inputPassword]').type(password)
        cy.get('#signIn').click()
        cy.url().should('eq', 'http://localhost:8000/money_out')
      });


     it('should error if user enters incorrect password', () => {

        cy.visit('http://localhost:8000/')
        cy.get('[id=inputEmail]').type(email)
        cy.get('[id=inputPassword]').type('12345')
        cy.get('#signIn').click()
        cy.get('#logInError').should('be.visible')
    });

  });
