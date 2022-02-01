

document.getElementById("form_contact").addEventListener("submit", function (e) {
    let name = $("#name").val();
    let email = $("#email").val();
    let subject = $("#subject").val();
    let message = $("#message").val();



    Email.send({
        SecureToken : "46d446dd-390e-40d8-b0b5-cc49dd4e3c53",
        To : 'charbelnickel@gmail.com',
        From : "leonbonou20@gmail.com",
        Subject : "Nickel Contact " + subject,
        Body : "  <strong>Les informations du contact</strong> <br><br><br>\n" +
            "  <table>\n" +
            "      <tr>\n" +
            "          <th style=\"text-align: left\">Nom: </th>\n" +
            "          <td>" + name + "</td>\n" +
            "      </tr>\n" +
            "      <tr>\n" +
            "          <th style=\"text-align: left\">Email: </th>\n" +
            "          <td>"+email+"</td>\n" +
            "      </tr>\n" +
            "      <tr>\n" +
            "          <th style=\"text-align: left\">Message: </th>\n" +
            "          <td>"+message+"</td>\n" +
            "      </tr>\n" +
            "  </table>",
    }).then(

    );

    e.preventDefault();
});
