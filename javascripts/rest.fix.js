$(function () {
    var url = 'https://api.github.com/repos/juanber84/dep/releases/latest';
    $.get(url, function (data) {
        var latestBrowserDownloadUrl = data['assets'][0]['browser_download_url'];
        $('.down-phar').attr('href',latestBrowserDownloadUrl);
        console.log(latestBrowserDownloadUrl)
    });
});