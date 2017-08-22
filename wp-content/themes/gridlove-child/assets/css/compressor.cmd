REM 压缩
C:\xampp\htdocs_hotlinks\wp-content\themes\gridlove-child>java -jar D:/WEB/yuicompressor-2.4.8.jar -o style.min.css style.css
REM 合并所有js，生成all.js
C:\xampp\htdocs_hotlinks\wp-content\themes\gridlove-child>copy style.min.css+assets\css\jquery.mCustomScrollbar.min.css assets\css\all.css /b
REM 删除临时文件
C:\xampp\htdocs_hotlinks\wp-content\themes\gridlove-child>del style.min.css
REM 压缩js，生成min.js
cd assets\css
C:\xampp\htdocs_hotlinks\wp-content\themes\gridlove-child\assets\css>java -jar D:/WEB/yuicompressor-2.4.8.jar -o min.css all.css
REM url中的相对路径需要检查和修改