<table class="tblSoal" border="0">
    <tbody>
        <tr>
            <td class="nomor align-baseline"><span>1.</span></td>
            <td>
                {!! $data->pertanyaan_text !!}
                @foreach ($data->pertanyaan_file as $item)
                    @if ($item->type_file=='gambar')
                    <div class="row">
                        <img src="{{url('uploads/elearning/pertanyaan')}}/{{$item->file}}" alt="gambar_{{$item->id_pertanyaan_file}}">
                    </div>
                    @endif
                @endforeach
                @foreach ($data->pertanyaan_file as $item)
                    @if ($item->type_file=='audio')
                    <div class="row">
                        <audio id="audioOutput" class="mx-auto mt-auto" controls src="{{url('uploads/elearning/pertanyaan')}}/{{$item->file}}"></audio>
                    </div>
                    @endif
                @endforeach
                @foreach ($data->pertanyaan_file as $item)
                    @if ($item->type_file=='video')
                    <div class="row">
                        {!! $item->file !!}
                    </div>
                    @endif
                @endforeach
                @foreach ($data->pertanyaan_file as $item)
                    @if ($item->type_file=='link')
                    <div class="row">
                        <a class="btn btn-primary" href="{{$item->file}}" target="_blank">Buka Link</a>
                    </div>
                    @endif
                @endforeach
                <form class="dataForm">
                    <input type="hidden" name="ids" value="{{ $ids }}">
                    <input type="hidden" name="nU" value="{{ $nU }}">
                    <input type="hidden" name="idjs" value="{{ $idjs }}">
                    <div class="pilihan d-flex flex-column my-4">
                        @foreach ($data->pilihan_jawaban as $item)
                        <label for="jawaban{{ $item->id_pilihan_jawaban }}" id="labelJawaban{{ $item->id_pilihan_jawaban }}">
                            <input type="radio" name="jawaban" id="jawaban{{ $item->id_pilihan_jawaban }}" value="{{ $item->prefix_pilihan }}"
                            @if ($strJs == $item->prefix_pilihan)
                                checked
                            @endif
                            >
                            {{$item->prefix_pilihan}}. {{$item->pilihan_text}}
                            <span class="l{{$item->prefix_pilihan}}" style="display: none">loading</span>
                            <span class="v{{$item->prefix_pilihan}}" style="display: none">V</span>
                        </label>
                        @endforeach
                    </div>
                </form>
            </td>
        </tr>
    </tbody>
</table>

<script>
    var prefixJs = $('input[name=jawaban]:checked').val()
    $('input[name=jawaban]').change(function(){
        const data = $('.dataForm').serialize()
        const nU = $('input[name=nU]').val()
        $('.l'+prefixJs).show()
        $.post("{{route('siswa.kerjakan.store')}}",data).done((data, status, xhr)=>{
            if(data.status == 'success'){
                $('.l'+prefixJs).hide()
                $('.v'+prefixJs).show()
                soalBoxDone(data.arrJs,nU)
            }
        }).fail((e)=>{
            $('.l'+prefixJs).hide()
            $('.v'+prefixJs).hide()
            $('input[name=jawaban]').prop('chacked',false)
            console.log(e)
        })
    })
</script>
