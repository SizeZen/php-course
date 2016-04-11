$(document).ready(function(){
    $('#addUser').click(function(e){
        var data = {
            "username": $('#username').val(),
            "userlogin": $('#userlogin').val(),
            "usertype": $('#usertype').val()
        };
        data = JSON.stringify(data);
        $.post('/api/addUser', data)
        .done(function(data, status, xhr) {
            var responseInfo = $('#responseinfo');
            responseInfo.removeClass();
            responseInfo.addClass('text-success');
            responseInfo.html("Запит виконано успішно");
            location.reload();
        })
        .fail(function(data, status, xhr) {
            var responseInfo = $('#responseinfo');
            responseInfo.removeClass();
            responseInfo.addClass('text-danger');
            responseInfo.html("Не правильні дані");
        });    
    });
    $('.fireUser').click(function(e){
        var data = {
            "id": $(e.currentTarget).parent().parent().get(0).firstElementChild.innerHTML
        };

        data = JSON.stringify(data);
        $.post('/api/fireUser', data)
        .done(function(data, status, xhr) {
            location.reload();
        })
    });
    $('.acceptUser').click(function(e){
        var data = {
            "id": $(e.currentTarget).parent().parent().get(0).firstElementChild.innerHTML
        };

        data = JSON.stringify(data);
        $.post('/api/acceptUser', data)
        .done(function(data, status, xhr) {
            location.reload();
        })
    });
    $('.updateUser').click(function(e) {
        var id = $(e.currentTarget).parent().parent().get(0).children[0].innerHTML;
        var name = $(e.currentTarget).parent().parent().get(0).children[1].innerHTML;

        $('#UpdateWorker .userid').val(id)
        $('#UpdateWorker .username').val(name)
    });
    $('#updateUser').click(function(e){
        var data = {
            "username": $('.username').val(),
            "userid": $('.userid').val()
        };

        data = JSON.stringify(data);
        $.post('/api/updateUser', data)
        .done(function(data, status, xhr) {
            location.reload();
        })
        .fail(function(data, status, xhr) {
            var responseInfo = $('#responseUpdateWorkerInfo');
            responseInfo.removeClass();
            responseInfo.addClass('text-danger');
            responseInfo.html("Не правильні дані");
        }); 
    });
    $('#addEmployeeSalary').click(function(e) {
        var data = {
            "employeeid": $("[name='inputEmployeeId']").val(),
            "employeesalary": $("[name='inputEmployeeSalary']").val()
        };
        console.log(data);
        data = JSON.stringify(data);
        $.post('/api/addSalary', data)
        .done(function(data, status, xhr) {
            location.reload();
        })
        .fail(function(data, status, xhr) {
            var responseInfo = $('#responseinfo');
            responseInfo.removeClass();
            responseInfo.addClass('text-danger');
            responseInfo.html("Не правильні дані");
        }); 
    });
    $('#updatePassword').click(function(e) {
        var data = {
            "oldpass": $("[name='oldpass']").val(),
            "newpass": $("[name='newpass']").val()
        };
        console.log(data);
        data = JSON.stringify(data);
        $.post('/api/resetPass', data)
        .done(function(data, status, xhr) {
            location.reload();
        })
        .fail(function(data, status, xhr) {
            var responseInfo = $('#responseUpdatePasswordInfo');
            responseInfo.removeClass();
            responseInfo.addClass('text-danger');
            responseInfo.html("Не правильні дані");
        }); 

    });
});
