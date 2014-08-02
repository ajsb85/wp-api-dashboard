(function( $, Marionette, Backbone, _ ) {
	app = window.app;
	app.RouterConstructor = Backbone.Router.extend({
	  routes: {
	    'posts':     'editPostsScreen'
	  },

	editPostsScreen: function() {
		app.Collection.Posts = new wp.api.collections.Posts();
		app.TableViewInstance = new app.View.TableView({
			collection: app.Collection.Posts
		});
		app.mainRegion.show( app.TableViewInstance );
		app.Collection.Posts.fetch({wait: true});
	  }
	});

})( jQuery, Marionette, Backbone, _ );
