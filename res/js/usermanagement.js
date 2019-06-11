
    function deleteUser(sender, userId){
        $.post(baseURL + "/utility/manageUserAsAdmin.php", {
            userId: userId,
            operation: "deleteUser"
        }, function(data, status){
            if(data != "-1"){
                $(sender).parent("td").parent("tr").remove();
            }
        });
    }
    
    function resetPassword(userId){
        $.post(baseURL + "/utility/manageUserAsAdmin.php", {
            userId: userId,
            operation: "resetPassword"
        }, function(data, status){
            if(data != "-1"){
                alert("E-Mail versendet!");
            }
            else{
                alert("Es ist ein Fehler aufgetreten!");
            }
        });
    }
    
    function toggleActive(sender, userId){
        $.post(baseURL + "/utility/manageUserAsAdmin.php", {
            userId: userId,
            operation: "toggleActive"
        }, function(data, status){
        });
    }