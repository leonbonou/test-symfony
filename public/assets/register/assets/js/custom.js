$(function () {
    let step_error = $("#second_step_error");
    let second_step = $("#second_step");
    let btn_submit = $("#btn_submit");

    step_error.hide();
    second_step.hide();

    btn_submit.click(function(e) {
        const capital = $("#capital").val();
        const duree = $("#duree").val();

        let setCapital = $("#setCapital");
        let setDuree = $("#setDuree");
        let setMens = $("#setMens");
        let setTotalMens = $("#setTotalMens");

        setCapital.text(capital);
        setDuree.text(duree);
        setMens.text((capital / duree) * 1.02 + ",00");
        setTotalMens.text(capital*1.02 + ",00");
        second_step.show();

    });

});