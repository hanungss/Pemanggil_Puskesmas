<?php
$nilai = 0; 

if (isset($_POST["navigasi"])) {
    $navigasi = $_POST["navigasi"];
    $nilai = isset($_POST["counter"]) ? (int)$_POST["counter"] : 0;
    
    if ($navigasi === "prev" && $nilai > 0) {
        $nilai--;
    } elseif ($navigasi === "next") {
        $nilai++;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Antrian Loket Pendaftaran Puskesmas Tamansari</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <form method="post" action="">
        <div>
            <input type="text" name="counter" class="counter" value="<?= $nilai ?>" readonly>
        </div>
        <div>
            <button name="navigasi" value="prev" class="navigasi">Prev</button>
            <button name="navigasi" value="next" class="navigasi">Next</button>
        </div>
        <div class="bigNumber">
            <?= $nilai ?>
        </div>
    </form>
</div>

<script>
    const antrian = <?= $nilai ?>;
    
    function numberToAudioSequence(n) {
        const suara = [];
        suara.push('panggilan.mp3');
        
        if (n === 0) {
            return suara;
        }
        
        suara.push('nomorantrian.mp3');
        
        const map = ["kosong","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan","sepuluh","sebelas"];
        
        function playTwoDigits(num) {
            const result = [];
            if (num === 0) return [];
            if (num <= 11) {
                result.push(map[num] + ".mp3");
            } else if (num < 20) {
                result.push(map[num - 10] + ".mp3");
                result.push("belas.mp3");
            } else {
                const puluh = Math.floor(num / 10);
                const satuan = num % 10;
                
                if (puluh === 1) {
                    result.push("sepuluh.mp3");
                } else {
                    result.push(map[puluh] + ".mp3");
                }
                result.push("puluh.mp3");
                
                if (satuan > 0) {
                    result.push(map[satuan] + ".mp3");
                }
            }
            return result;
        }
        
        if (n > 0) {
            const ribu = Math.floor(n / 1000);
            const ratus = Math.floor((n % 1000) / 100);
            const sisa = n % 100;
            
            if (ribu > 0) {
                if (ribu === 1) {
                    suara.push("seribu.mp3");
                } else {
                    suara.push(map[ribu] + ".mp3");
                    suara.push("ribu.mp3");
                }
            }
            
            if (ratus > 0) {
                if (ratus === 1) {
                    suara.push("seratus.mp3");
                } else {
                    suara.push(map[ratus] + ".mp3");
                    suara.push("ratus.mp3");
                }
            }
            
            suara.push(...playTwoDigits(sisa));
            
            suara.push("menujuloket.mp3");
        }
        
        return suara;
    }
    
    function playAudioQueue(files, index = 0) {
        if (index >= files.length) return;
        const audio = new Audio('suara/' + files[index]);
        audio.onended = () => playAudioQueue(files, index + 1);
        audio.play();
    }
    
    const files = numberToAudioSequence(antrian);
    window.onload = () => playAudioQueue(files);
    
</script>
</body>
</html>