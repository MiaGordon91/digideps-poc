describe('money out page', () => {

    beforeEach(() => {
        cy.visit('http://localhost:8000/money_out')
    })

    it('user successfully downloads money out file', () => {
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
