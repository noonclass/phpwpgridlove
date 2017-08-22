REM 压缩ias，不加扰
C:\xampp\htdocs_hotlinks\wp-content\themes\gridlove-child\assets\js>java -jar D:/WEB/yuicompressor-2.4.8.jar --nomunge -o jquery-ias.min.js jquery-ias.js
REM 合并所有js，生成all.js
C:\xampp\htdocs_hotlinks\wp-content\themes\gridlove-child\assets\js>copy jquery.mCustomScrollbar.concat.min.js+jquery-ias.min.js+Comment.js+Profile.js all.js /b
REM 压缩js，生成min.js
C:\xampp\htdocs_hotlinks\wp-content\themes\gridlove-child\assets\js>java -jar D:/WEB/yuicompressor-2.4.8.jar -o min.js all.js