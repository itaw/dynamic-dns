$(document).ready(function () {

    $('#form-modal-add-domain').submit(function (e) {
        e.preventDefault();

        var name = $('#modal-input-domain-name').val();

        if (name !== '') {
            var url = Routing.generate('domains_create');

            $.post(url, {
                name: name,
                sent: 1,
                ajax: 1
            }).done(function (data) {
                console.log(data);
            });
        } else {
            alert('All fields must be filled!');
        }
    });

});