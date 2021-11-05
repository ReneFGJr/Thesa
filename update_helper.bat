echo off

echo off
echo "Language"
mkdir app\Language\pt-BR
copy ..\Brapci3.0\app\Language\pt-BR\social.* app\Language\pt-BR\*.* 

echo "Copiando Helper"
copy ..\Brapci3.0\app\Helpers\*.* app\Helpers\*.* 
copy ..\Brapci3.0\app\Models\Social*.* app\Models\*.*

#echo "RDP"
#mkdir app\Models\Rdf

#copy ..\Brapci3.0\app\Models\RDF\RDF*.php app\Models\RDF\*.* 

echo "Images"
copy ..\Brapci3.0\app\Models\Images.php app\Models\*.* 

echo "IO"
mkdir app\Models\Io
copy ..\Brapci3.0\app\Models\Io\*.php app\Models\Io\*.* 
