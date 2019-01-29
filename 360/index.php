<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="../styles/360.css">
    <script src="https://naver.github.io/egjs-view360/common/js/jquery-2.2.4.js"></script>
    <script src="..\node_modules\@egjs\view360\dist\view360.pkgd.js"></script>
</head>
<body>

    <div id="myPanoViewer" class="viewer"></div>

    <script>
        // create PanoViewer with option
        var PanoViewer = eg.view360.PanoViewer;
        const panoViewer = new PanoViewer(
            document.getElementById("myPanoViewer"),
            {
                image: "img.jpg"
            }
        );   
    </script>


    
    
</body>
</html>