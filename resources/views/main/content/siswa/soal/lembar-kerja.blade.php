<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{csrf_token()}}">
	<link rel="shortcut icon" href="{{isset($identitas)?asset('uploads/identitas/'.$identitas->favicon):asset('assets/img/logo/logo.png')}}" type="image/x-icon">
	<title>ELEARNING - LEMBAR KERJA SISWA</title>
	@include('main.include.style') <!--importCSS-->
    <style>
        .sidebar-soal{
            background-color: #008cff;
            padding: 10px 30px;
            height: 100vh;
            position: sticky;
            top: 0;
        }
        .timer{
            margin: auto;
            width: 150px;
            height: 50px;
            color: #fff;
            border: 1px solid #fff;
            border-radius: 10px;
            font-size: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .timer-danger{
            color: #cf1010;
            font-weight: 700;
        }
        .soalsoal{
            margin: auto;
            max-width: 200px;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 3px;
            flex-wrap: wrap;
        }
        .soal-box{
            width: 35px;
            height: 35px;
            border: 1px solid #000;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
        .soal-box:hover{
            background-color: #008cff;
            border: 1px solid #008cff;
            color: #fff;
            cursor: pointer;
        }
        .soal-box-proses{
            background-color: #008cff !important;
            border: 1px solid #008cff !important;
            color: #fff;
        }
        .soal-box-done{
            background-color: #17a00e;
            border: 1px solid #17a00e;
            color: #fff;
        }
        .soal-box.disabled {
            pointer-events: none;
            opacity: 0.5;
        }
        #loadingMapNomor{
            margin: 100px 0;
        }
    </style>
</head>
<body class="bg-white">
    <input type="hidden" name="ids" value="{{ $soal->id_soal }}">
    <input type="hidden" name="idjs" value="{{ $id_jawaban_siswa }}">
    <div class="row">
        <div class="col-9">
            <div class="container soal-base">
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="p-1 mx-auto d-grid text-center fw-bold" style="line-height: 0.1">
                            <img src="{{ asset('admin/assets/images/avatars/no-avatar.png')}}" class="rounded-circle mx-auto my-4" alt="..." width="70">
                            <p>{{ $bio->nisn != '' ? $bio->nisn  : 'NISN' }} - {{ $bio->nama }}</p>
                            <p>Kelas Saat ini - Nama Mata Pelajaran</p>
                            <p>{{ $soal->judul_soal }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12" id="soal">

                    </div>
                    <hr>
                    <div class="col-12 d-flex mb-4">
                        <div class="p-1 mx-auto btnNav">
                            <button class="btn btn-secondary btnPrev d-inline">Sebelumnya</button>
                            <button class="btn btn-primary btnNext">Selanjutnya</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3 sidebar-soal">
            <div class="timer">
            </div>
            <hr>
            <div class="card">
                <div class="card-header text-center">
                    Soal
                </div>
                <div class="card-body">
                    <div class="soalsoal">
                        @foreach ($pertanyaans as $item)
                            <div class="soal-box" id="soal-box-{{ $item->nomor }}" onclick="contentSoal('{{ $item->nomor }}')">{{ $item->nomor }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
	@include('main.include.script') <!--importJavaScript-->
    <!--Sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		$(document).ready(()=>{
            contentSoal()
            countDownMengerjakan()
		})

        function countDownMengerjakan(){
            var batasWaktu = new Date("{{ $batas_waktu }}");

            var x = setInterval(function() {
                var now = new Date().getTime();
                var distance = batasWaktu.getTime() - now;

                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                hours = (hours < 10) ? "0" + hours : hours;
                minutes = (minutes < 10) ? "0" + minutes : minutes;
                seconds = (seconds < 10) ? "0" + seconds : seconds;

                $(".timer").html(hours + ":" + minutes + ":" + seconds);

                if (distance < 61000) {
                    $(".timer").addClass("timer-danger");
                }

                if (distance < 0) {
                    clearInterval(x);
                    $(".timer").html("00:00:00");
                    witingTime("Waktu Habis!")
                }
            }, 1000);

        }

        let btnSelesai = `
            <button class="btn btn-danger btnSelesai" onclick="selesaiMengerjakan()">Selesai</button>
        `
        let loading = `
            <div class="spinner-div text-center" id="loadingMapNomor">
                <div class="m-auto">
                    <span class="spinner-border" role="status" aria-hidden="true"></span><br> <span>Loading...</span>
                </div>
            </div>
        `
        function contentSoal(nU=1){
            $('.soal-box').removeClass('soal-box-proses')
            $('.soal-box').addClass('disabled')
            $('.btnNav>.btn').prop('disabled',true)
            const ids = $('input[name=ids]').val()
            const idjs = $('input[name=idjs]').val()
            $('#soal').html(loading)
            $.get("{{route('siswa.kerjakan.contentSoal')}}",{ids,idjs,nU}).done((data, status, xhr)=>{
                if(data.status == 'success'){
                    if(nU == data.current){
                        soalBoxDone(data.arrJs,nU)
                    }
                    if(data.prev){
                        $('.btnPrev').removeAttr('disabled')
                        $('.btnPrev').attr('onclick',`contentSoal(${data.prev})`)
                    }else{
                        $('.btnPrev').prop('disabled',true)
                        $('.btnPrev').removeAttr('onclick')
                    }

                    if(data.current != data.lastNumber){
                        $('.btnNav .btnSelesai').remove()
                        $('.btnNext').attr('onclick',`contentSoal(${data.next})`)
                        $('.btnNext').removeAttr('disabled')
                        $('.btnNext').show()
                    }else{
                        $('.btnNav').append(btnSelesai)
                        $('.btnNext').hide()
                    }

                    $('#soal').html(data.content).fadeIn()

                    $('.soal-box').removeClass('disabled')
                }
            }).fail((e)=>{
                console.log(e)
            })
        }

        function soalBoxDone(dataJs,nU){
            $.each(dataJs,(i,v)=>{
                if(v != 0){
                    $('#soal-box-'+(i+1)).addClass('soal-box-done')
                }
            })
            if(nU){
                $('#soal-box-'+nU).removeClass('soal-box-proses')
                $('#soal-box-'+nU).addClass('soal-box-proses')
            }
        }

        function witingTime(title){
            Swal.fire({
                title: title,
                html: 'Ujian selesai dikerjakan, <br>tunggu anda akan dialihkan ke dashboard siswa',
                icon: "success",
                showConfirmButton: false,
                timer: 2500
            })
            $.post("{{route('siswa.kerjakan.selesaikan')}}",{id_soal:$('input[name=ids]').val()})
            setTimeout(() => {
                window.location = "{{ route('siswa.dashboard') }}"
            }, 2500);
        }

        function selesaiMengerjakan(){
            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi',
                html: 'Anda yakin ingin mengakhiri ujian ini?, <br>periksa kembali dan pastikan jawaban anda!',
                showCancelButton: true,
                confirmButtonText: "Ya, akhiri"
            }).then((result) => {
                if (result.isConfirmed) {
                    witingTime("Terimakasih")
                }
            });
        }
	</script>
</body>
</html>
