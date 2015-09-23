$(document).ready(function ()
{
    var id = $(":hidden[name=id]").val();

    $('#submit').click(function ()
    {
        event.preventDefault();

        $.ajax({
            async: true,
            url: '/new/url',
            type: 'post',
            data: {'id': id},
            success: function (data)
            {
                $('.row').append('Ваша новая ссылка: ').append(data);
            }
        });
    });
});