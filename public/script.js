$(document).ready(function ()
{
    var url = window.location.pathname.slice(1);

    $('#submit').click(function ()
    {
        event.preventDefault();

        $.ajax({
            async: true,
            url: '/new/url',
            type: 'post',
            data: {'url': url},
            success: function (data)
            {
                console.log(data);
                $('#result').append('The new one-time link: ').append(data);
            }
        });
    });
});