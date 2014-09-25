angular.module('mainCtrl', [])

	.controller('mainController', function($scope, $http, Comment) {
		// object to hold all the data for the new comment form
		$scope.commentData = {};

		// loading variable to show the spinning loading icon
		$scope.loading = true;
		
		// get all the comments first and bind it to the $scope.comments object
		Comment.get()
			.success(function(data) {
				$scope.comments = data;
				$scope.loading = false;
			});


		// function to handle submitting the form
		$scope.submitComment = function() {
			$scope.loading = true;

			// save the comment. pass in comment data from the form
			Comment.save($scope.commentData)
				.success(function(data) {

					// if successful, we'll need to refresh the comment list
					Comment.get()
						.success(function(getData) {
							$scope.comments = getData;
							$scope.loading = false;
						});

				})
				.error(function(data) {
					console.log(data);
				});
		};

		// function to handle deleting a comment
		$scope.deleteComment = function(id) {
			$scope.loading = true; 

			Comment.destroy(id)
				.success(function(data) {

					// if successful, we'll need to refresh the comment list
					Comment.get()
						.success(function(getData) {
							$scope.comments = getData;
							$scope.loading = false;
						});

				});
		};

	})

	.controller('videoCommentController', function($scope, $http, VideoComment) {
		// object to hold all the data for the new comment form
		$scope.commentData = {};

		// loading variable to show the spinning loading icon
		$scope.loading = true;
		
		// get all the comments first and bind it to the $scope.comments object

		//see http://www.linkplugapp.com/a/224929 
		
		VideoComment.get(init_video_id)
			.success(function(data) {
				$scope.comments = data;
				$scope.loading = false;
			});

		// function to handle submitting the form
		$scope.submitComment = function() {
			$scope.loading = true;

			// save the comment. pass in comment data from the form
			VideoComment.save($scope.commentData)
				.success(function(data) {

					// if successful, we'll need to refresh the comment list
					VideoComment.get(init_video_id)
						.success(function(getData) {
							$scope.comments = getData;
							$scope.commentData.text = '';
							$scope.loading = false;
						});

				})
				.error(function(data) {
					console.log(data);
				});
		};

		// function to handle deleting a comment
		$scope.deleteComment = function(id) {
			$scope.loading = true; 

			VideoComment.destroy(id)
				.success(function(data) {

					// if successful, we'll need to refresh the comment list
					VideoComment.get(init_video_id)
						.success(function(getData) {
							$scope.comments = getData;
							$scope.loading = false;
						});

				});
		};

		$scope.showComment = function(comment, current_user_id) {			
			return 	comment.user_id == current_user_id;		
		};

	});