@extends('layouts.app')

@section('js_page')
    <script type="text/javascript">
        $(function() {
            $("#submitForm").click(function(e) {
                var url = "{{ route('sheet.store', ['version_id' => $sheet->version_id]) }}"; // the script where you handle the form input.

                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#idForm").serialize(), // serializes the form's elements.
                    success: function(data)
                    {
                        console.log(data); // show response from the php script.
                        var result;
                        result = '<tr class="data-'+data.id+'">';
                        result += '<td>'+data.row+'</td>';
                        result += '<td>'+data.kolom+'</td>';
                        result += '<td>'+data.validation_type+'</td>';
                        result += '<td>'+data.validation+'</td>';
                        result += '<td>'+data.query_value+'</td>';
                        result += '<td>'+data.sequence+'</td>';
                        result += '<td><span onclick="deletedata('+data.id+')" class="btn btn-success pull-right">Delete</span></td>';
                        result += '</tr>';
                        $('#result').prepend(result);
                    }
                });

                e.preventDefault(); // avoid to execute the actual submit of the form.
            });


        });

        function deletedata(id) {
            var url = "{{ route('sheet.destroy', ['version_id' => $sheet->version_id, 'id' => '']) }}/"+id; // the script where you handle the form input.



            var form = '';
            form += "_method=DELETE";
            form += "&_token={{ csrf_token() }}";

            $.ajax({
                type: "POST",
                url: url,
                data: form, // serializes the form's elements.
                success: function(data)
                {
                    $('.data-'+id).remove();
                }
            });
        }
    </script>
@endsection

@section('content')
    <h1> Setting Sheet</h1>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Form Setting</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <a href="{{ route('sheet.index', $sheet->version_id) }}" class="btn btn-primary"> Back </a>
                    <br />
                    <div class="form-group scroll" style="width: 100%;">
                        <table class="table table-border">
                            <tbody id='form_input_setting' style="height: 90px;">
                                <tr>
                                    <td width="80">Baris</td>
                                    <td width="80">Kolom</td>
                                    <td width="150">Validation Type</td>
                                    <td width="30%">Validation</td>
                                    <td width="30%">Query</td>
                                    <td width="80">Sequence</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <form id="idForm" class="form-horizontal form-label-left">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="id" value="{{ $sheet->id }}">
                                        <td><input type="text" name="row" required="required" class="form-control"></td>
                                        <td><input type="text" name="kolom" required="required" class="form-control"></td>
                                        <td>
                                            <select name="validation_type" class="form-control">
                                                <option value="none"> None </option>
                                                <option value=">"> > </option>
                                                <option value="<"> < </option>
                                                <option value=">="> >= </option>
                                                <option value="<="> <= </option>
                                                <option value="="> = </option>
                                                <option value="!="> != </option>
                                                <option value="is_null"> is Null </option>
                                                <option value="is_not_null"> is Not Null </option>
                                                <option value="array"> Array </option>
                                                <option value="array_master"> Array Master </option>
                                                <option value="rumus"> Rumus </option>
                                                <option value="query"> Query </option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="validation" required="required" class="form-control"></td>
                                        <td><input type="text" name="query_value" required="required" class="form-control"></td>
                                        <td><input type="text" name="sequence" required="required" class="form-control"></td>
                                        <td><button id="submitForm" class="btn btn-success pull-right">Save</button></td>
                                    </form>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-border">
                            <tbody id="result">
                            @foreach($setting as $row)
                                <tr class="data-{{ $row->id }}">
                                    <td>{{ $row->row }}</td>
                                    <td>{{ $row->kolom }}</td>
                                    <td>{{ $row->validation_type }}</td>
                                    <td>{{ $row->validation }}</td>
                                    <td>{{ $row->query_value }}</td>
                                    <td>{{ $row->sequence }}</td>
                                    <td><span onclick="deletedata({{ $row->id }})" class="btn btn-success pull-right">Delete</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
