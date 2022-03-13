@extends("layouts.layout")

@section("main")
<!-- 選單 -->
@include("layouts.backend_siderbar")
<!-- 寫主內容 -->

<div class="main col-md-9 p-0 d-flex flex-wrap align-items-start">
    <div class="col-8 border py-3 text-center">後台管理區</div>
    <button class="col-4 btn-light border py-3 text-center">管理登出</button>
    <div class="border w-100 p-1" style="height: 500px;overflow:auto;">
        <h5 class="text-center border-bottom py-3">
            @if($module!='Total' && $module !='Bottom')

            <button class="btn btn-sm btn-primary float-left" id="addRow">新增</button>
            @endif

            {{ $header }}
        </h5>
        <table class="table border-none text-center">
            <tr>
                @isset($cols)
                @if($module != 'Total' && $module != 'Bottom')

                @foreach($cols as $col)
                <td width="{{ $col }}">{{ $col }}</td>
                @endforeach

                @endif
                @endisset

                <!-- 第一版 -->
                <!-- <td width="">網站標題</td>
                <td width="">替代文字</td>
                <td width="10%">操作</td>
                <td width="10%">顯示</td>
                <td width="10%">刪除</td> -->
            </tr>
            @isset($rows)
            @if($module != 'Total' && $module != 'Bottom')

            @foreach($rows as $row)
            <tr>
                @foreach($row as $item)
                <td>
                    @switch($item['tag'])
                        @case('img')
                            @include('layouts.img',$item)
                        @break

                        @case('button')
                            @include('layouts.button',$item)
                        @break

                        @case('embed')
                            @include('layouts.embed',$item)
                        @break

                        @case('textarea')
                            @include('layouts.textarea',$item)
                        @break

                        @default
                    {{ $item['text'] }}


                    @endswitch
                </td>
                @endforeach
            </tr>
            @endforeach
            @else
            <tr>
                <td>{{ $cols[0] }}</td>
                <td>{{$rows[0]['text']}}</td>
                <td>@include("layouts.button",$rows[1])</td>
            </tr>

            @endif
            @endisset


        </table>

    </div>
</div>
@endsection

@section("script")
<!-- 寫js -->
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#addRow").on("click", function() {
        $.get("/modals/add{{ $module }}", function(modal) {
            $("#modal").html(modal)
            $("#baseModal").modal("show")

            $("#baseModal").on("hidden.bs.modal", function() {
                $("#baseModal").modal("dispose")
                $("#modal").html("")
            })
        })
    })


    $(".edit").on("click", function() {
        let id = $(this).data('id');
        $.get(`/modals/{{ strtolower($module) }}/${ id }`, function(modal) {
            $("#modal").html(modal)
            $("#baseModal").modal("show")

            $("#baseModal").on("hidden.bs.modal", function() {
                $("#baseModal").modal("dispose")
                $("#modal").html("")
            })
        })

    })

    $(".delete").on("click", function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'delete',
            url: `/admin/{{ strtolower($module) }}/${id}`,
            scuccess: function() {
                location.reload(true)
            }
        })
    })

    $(".show").on("click", function() {
        let id = $(this).data('id');
        $.ajax({
            type: 'patch',
            url: `/admin/{{ strtolower($module) }}/sh/${id}`,
            success: function() {
                location.reload(true)
            }

        })
    })
</script>
@endsection