var app = angular.module('application',['ngRoute','ngResource'])

app.config(function($routeProvider){
	$routeProvider
	.when('/', {
		templateUrl: '/view/todos.html',
		controller: 'todo-list'
	})
	.otherwise({
		redirectTo: '/'
	})
})

