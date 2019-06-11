
    function submitProfileData(){
        let pwd = $("#profilePwd").val();
        let firstname = $("#profileFirstname").val();
        let lastname = $("#profileLastname").val();
        let email = $("#profileEmail").val();
        $.post(baseURL + "/utility/edituserdata.php", {
            pwd: pwd,
            firstname: firstname,
            lastname: lastname,
            email: email
        }, function(data, status){
            let html = "<div class='alert alert-primary' role='alert'>"+ data + "</div>";
            $("#profileUpdateInfo").html(html);
        });
    }