(function( $, Marionette, Backbone, _ ) {
	window.app = app = new Marionette.Application({
		View: {},
		Collection: {},
		Model: {}
	});

	app.RouterConstructor = Backbone.Router.extend({
		routes: {
			'': 'index',
			'posts/': 'editPostsScreen',
			'pages/': 'editPagesScreen'
		},

		index: function() {},

		editPostsScreen: function() {
			app.View.TableView = Marionette.CompositeView.extend({
				childView: app.View.PostRowView,
				// specify a jQuery selector to put the `childView` instances into
				childViewContainer: "tbody",

				template: "#table-template"
			});
			app.Collection.Posts = new wp.api.collections.Posts();
			app.TableViewInstance = new app.View.TableView({
				collection: app.Collection.Posts
			});
			app.mainRegion.show( app.TableViewInstance );
			app.Collection.Posts.fetch( {wait: true} );
		},

		editPagesScreen: function() {
			app.View.TableView = Marionette.CompositeView.extend({
				childView: app.View.PostRowView,
				// specify a jQuery selector to put the `childView` instances into
				childViewContainer: "tbody",

				template: "#table-template"
			});
			app.Collection.Pages = new wp.api.collections.Pages();
			app.TableViewInstance = new app.View.TableView({
				collection: app.Collection.Pages
			});
			app.mainRegion.show( app.TableViewInstance );
			app.Collection.Pages.fetch( {wait: true} );
		}
	});

	app.View.PostRowView = Marionette.ItemView.extend({
		tagName: "tr",
		className: "row-view",
		template: "#post-preview",
		events: {
			'click .edit-post': 'handleEditPostClick',
			'change input': 'handleInputChange',
			'change textarea': 'handleInputChange',
			'click .save-post': 'handleSavePostClick'
		},

		handleEditPostClick: function( event ) {
			this.setEditMode();
		},

		setPreviewMode: function() {
			this.options.template = '#post-preview';
			this.render();
		},

		setEditMode: function() {
			this.options.template = '#post-edit';
			this.render();
		},

		handleInputChange: function( event ) {
			var $target = $(event.target),
				value = $target.val();
			var attributeName = $target.attr( 'class' );
			this.model.set( attributeName, value );
		},

		handleSavePostClick: function( event ) {
			this.$('.spinner').spin();
			this.model.save( {}, {
				success: _.bind( this.saveSuccessCallback, this ),
				error: _.bind( this.saveErrorCallback, this ),
			});
		},

		saveSuccessCallback: function( model, response, options ) {
			this.$('.spinner').spin( false );
			this.setPreviewMode();
		},

		saveErrorCallback: function( model, response, options ) {
			$('#alert-modal .modal-title').html(response.status + ': ' + response.statusText );
			if ( response.responseJSON && response.responseJSON[0] && response.responseJSON[0].message )
				$('#alert-modal .modal-body').html( response.responseJSON[0].message );
			this.$('.spinner').spin( false );
			$('#alert-modal').modal();
		}
	});


	app.addRegions({
		mainRegion: "#main-region"
	});

	app.addInitializer(function(options){
		app.Router = new app.RouterConstructor();
		Backbone.history.start({
			pushState: true,
			root: WP_API_Dash_Settings.root
		});
	});

	$(function() {
		app.start();
	});

})( jQuery, Marionette, Backbone, _ );