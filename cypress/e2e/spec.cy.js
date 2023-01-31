describe('registration', () => {

  beforeEach(() => {
    cy.visit('http://localhost:8000/register')
    cy.get('[id=registration_form_email]').type('test200@email.co')
    cy.get('[id=registration_form_plainPassword]').type('1234567')
  })

  it('user successfully registers ', () => {

    cy.get('[type="checkbox"]').check()

    cy.get('#registerButton').click()

    //user should be redirected to /dashboard
    cy.url().should('include', '/dashboard')
    cy.url().should('eq', 'http://localhost:8000/dashboard')
  })
})
