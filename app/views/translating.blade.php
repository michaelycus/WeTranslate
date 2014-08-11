@extends('layouts.main')

@section('content')
	<div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Translating</h1>
            </div>            
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Videos in translation
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Links</th>
                                        <th>Duration</th>
                                        <th>Start Date</th>
                                        <th>Who is working...</th>
                                        <th>Tags</th>
                                    </tr>
                                </thead>
                                <tbody>
                            	@foreach ($videos as $video)
                            	<tr>
                            		<td>{{ $video->title }}</td>
                            		<td>{{ $video->original_link .' - '. $video->working_link }}</td>
                            		<td>{{ $video->duration }}</td>
                            		<td>{{ date("Y/m/d",strtotime($video->created_at)) }}</td>
                            		<td> - </td>
                            		<td> - </td>
								</tr>
								@endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
    	</div>
	</div>
@stop