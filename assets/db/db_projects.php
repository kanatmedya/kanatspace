<?php
    include "assets/db/db_connect.php";
    
    global $conn;
    global $show_err;
    
    $show_err = "";
    
    if (isset($_POST['gorevata'])){
    
        $proje_adi = $_POST['proje_adi'];
        $proje_turu = $_POST['proje_turu'];
        $durum = $_POST['durum'];
        $durumlar = "deneme";
        $musteri = $_POST['musteri'];
        $teslim_tarihi = $_POST['teslim_tarihi'];
        $sorumlu1 = $_POST['sorumlu1'];
        $sorumlu2 = $_POST['sorumlu2'];
        $sorumlu3 = $_POST['sorumlu3'];
        $gorev_aciklama = $_POST['gorev_aciklama'];
    
        if (empty($proje_adi)){
            $show_err = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Bilgileri Eksiksiz Giriniz!</strong> <br> Proje Adını giriniz!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                         </div>';
        }else{
    
            $sql = "INSERT INTO gorev ('proje_adi', 'proje_turu', 'durum','durumlar',  'musteri', 'teslim_tarihi', 'sorumlu1', 'sorumlu2', 'sorumlu3', 'gorev_aciklama') VALUES ('$proje_adi' , '$proje_turu' , '$durum' , '$durumlar', '$musteri' , '$teslim_tarihi' , '$sorumlu1' , '$sorumlu2' , '$sorumlu3' , '$gorev_aciklama')";
            
            
            if ($conn->query($sql) === TRUE) {
              echo "New record created successfully";
            } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
?>