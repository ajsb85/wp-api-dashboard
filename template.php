<html>
<head>
	<title></title>
	<?php wp_head(); ?>
	<style>
		.row-view .title {
			margin-right: 10px;
		}
		.row-view .post-id {
			width: 100px;
		}
		.row-view fieldset {
			width: 33%;
		}
		.row-view fieldset label {
			font-weight: 400;
			margin: .2em 0;
		}
		.row-view fieldset label span.field-name {
			display: block;
			float: left;
			width: 5em;
		}
		.row-view fieldset label input {
			display: block;
			margin-left: 5em;
		}
	</style>
</head>
<body>
	<h1><?php echo get_bloginfo( 'name' ); ?></h1>
	<nav>
		<ul class="nav nav-tabs" role="tablist">
			<li><a href="/dash/posts/">Posts</a></li>
			<li><a href="/dash/pages/">Pages</a></li>
		</ul>
	</nav>
	<div id="main-region"></div>


	<script id="post-preview" type="text/html">
		<td class="post-id"><%= ID %></td>
		<td><span class="title"><%= title %></span><button class="edit-post btn btn-primary btn-s ladda-button">Edit</button></td>
	</script>
	<script id="post-edit" type="text/html">
		<td class="post-id"><%= ID %></td>
		<td>
			<fieldset>
				<label>
					<span class="field-name">Title</span>
					<input class="title" value="<%= title %>">
				</label>
				<label>
					<span class="field-name">Slug</span>
					<input class="slug" value="<%= slug %>">
				</label>
				<label>
					<span class="field-name">Post Type</span>
					<input class="type" readonly value="<%= type %>">
				</label>
			</fieldset>
			<fieldset>
				<label>
					<span class="field-name">Content</span>
					<textarea class="content"><%= content %></textarea>
				</label>
			</fieldset>
			<button class="save-post btn btn-primary btn-s">Save</button>
			<span class="spinner"></span>
		</td>
	</script>
	<script id="table-template" type="text/html">
		<table class="table table-striped">
			<thead>
				<td class="post-id">ID</td>
				<td></td>
			</thead>
			<tbody></tbody>
			<tfoot></tfoot>
		</table>
	</script>

		<!-- Modal -->
	<div class="modal fade" id="alert-modal" tabindex="-1" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
	      </div>
	      <div class="modal-body">
	        ...
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>


</body>
</html>