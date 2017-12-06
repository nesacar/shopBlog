@if(count($num) > 0)
    {!! Form::open(['action' => ['TablesController@store'], 'method' => 'POST']) !!}
    <div class="panel-heading clearfix">
        <h3 class="panel-title">Naziv modela</h3>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="exampleInputTable">Naziv tabele</label>
                <input type="text" class="form-control" name="table" placeholder="Tabela">
            </div>
        </div>
    </div>
    <div class="panel-heading clearfix">
        <h3 class="panel-title">Polja u tabeli</h3>
    </div>
    @for($i=0;$i<$num;$i++)
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="exampleInputTitle">Naziv polja</label>
                <input type="text" class="form-control" name="field[]" placeholder="Naziv">
            </div>
        </div>
        <div class="col-md-4">
            <label for="type">Tip polja:</label>
            <select class="sele" name="type[]">
                <option value="1" selected>Integer</option>
                <option value="2">String</option>
                <option value="3">Text</option>
                <option value="4">Boolean</option>
                <option value="5">Timestamp</option>
                <option value="6">Increments</option>
            </select>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="sel1">Primary</label>
                <div class="cekaj">
                    {!! Form::select('primary[]', [0 => 'ne', 1 => 'da'], 0, ['class' => 'sele']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="sel1">Index</label>
                <div class="cekaj">
                    {!! Form::select('index[]', [0 => 'ne', 1 => 'da'], 0, ['class' => 'sele']) !!}
                </div>
            </div>
        </div>
    </div><!-- .row -->
    @endfor
    @for($i=0;$i<15;$i++)
        <div class="row hidden">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exampleInputTitle">Naziv polja</label>
                    <input type="text" class="form-control" name="field[]" placeholder="Naziv">
                </div>
            </div>
            <div class="col-md-4">
                <label for="type">Tip polja:</label>
                <select class="sele" name="type[]">
                    <option value="1" selected>Integer</option>
                    <option value="2">String</option>
                    <option value="3">Text</option>
                    <option value="4">Boolean</option>
                    <option value="5">Timestamp</option>
                    <option value="6">Increments</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="sel1">Primary</label>
                    <div class="cekaj">
                        {!! Form::select('primary[]', [0 => 'ne', 1 => 'da'], 0, ['class' => 'sele']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="sel1">Index</label>
                    <div class="cekaj">
                        {!! Form::select('index[]', [0 => 'ne', 1 => 'da'], 0, ['class' => 'sele']) !!}
                    </div>
                </div>
            </div>
        </div><!-- .row -->
    @endfor
    <div class="row">
        <div class="col-md-12">
            <input type="submit" class="btn btn-success" value="kreiraj">
        </div>
    </div><!-- .row -->
    {!! Form::close() !!}
@endif
