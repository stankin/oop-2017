app.controller('todo-list',function($scope,$http,$rootScope){

	$http.get("/test/database.json")
	.then(function(response){
		console.log(response)
		$scope.lists = response.data.lists; 
		console.log($scope.lists)
	});

	$scope.list = {
		title:"",
		text:""
	};

	$scope.add_task = function(){
		console.log($scope.lists)
		var f_list = $scope.lists.find(function(i){
			return i.title == $scope.list.title_selected
		})

		var index = $scope.lists.indexOf(f_list)
		if (index != -1){
			$scope.lists[index].todos.push({text:$scope.list.text})
		}else{
			$scope.lists.push({title:$scope.list.title,todos:[{text:$scope.list.text}]})
		}
		

		$scope.close_modal();
		
	}

	$scope.close_modal = function(){
		$('#create_div').hide();
        $('#add_new_todo').show();
        $('#shadow_overlay').css('display','none');
	}

	$rootScope.$on('$viewContentLoaded', function(event){
		console.log("init")
		 $('#create_div').hide();
	     $('#shadow_overlay').css('display','none');
	     $('#project_title').css('display','none');


	///cloase add todo
	     $("#add_new_todo").click(function (event) {
	       console.log("onClickEvent() called")
	        $('#create_div').show();
	        $('#add_new_todo').toggle();
	        $('#shadow_overlay').css('display','block');

	        //turn off title field
	        $('#project_title').css('display','none');
	      //  reset_fields();
	    });

	     $('#hide_new_todo').click(function (event) {
	       console.log("onClickEvent() called")
	        $('#create_div').hide();
	        $('#add_new_todo').show();
	        $('#shadow_overlay').css('display','none');

	    });
	    /////////////fields letters
	   $('#project_title').click(function (event) {
	     $('#project_title').removeAttr('value');
	     $('#project_title').css('font-size', '17px');
	     $('#project_title').css('color', 'black');
	   });

	   $('#project_text').click(function (event) {
	     $('#project_text').removeAttr('value');
	     $('#project_text').css('font-size', '17px');
	     $('#project_text').css('color', 'black');
	   });

	   /////////// SELECT
	   $('select').select2({
	     'width': '340px',
	    	placeholder: "Категория",
	    	allowClear: false,
	    	minimumResultsForSearch: -1
	    }).on('change', function (e) {
	    //var str = $("#s2id_search_code .select2-choice span").text();
	    console.log("changed");
	    var selected_index = document.getElementById('select_category').options.selectedIndex;
	    var selected_value = document.getElementById('select_category').options[selected_index].value;
	    console.log("selected index = "+selected_index+" selected value= "+selected_value);
	    document.getElementById('project_title').setAttribute('value',selected_value);

	    if(selected_value=='Создать новый список')
	    {
	      $('#project_title').css('display','block');
	      document.getElementById('project_title').setAttribute('value','Заголовок');
	    }
	  });

	})
})

app.directive('icheck', ['$timeout', '$parse', function($timeout, $parse) {
    return {
      restrict: 'A',
      require: '?ngModel',
      link: function(scope, element, attr, ngModel) {
        $timeout(function() {
          var value = attr.value;
        
          function update(checked) {
            if(attr.type==='radio') { 
              ngModel.$setViewValue(value);
            } else {
              ngModel.$setViewValue(checked);
            }
          }


          $(element).iCheck({
		    checkboxClass: 'icheckbox_square-blue',
		    radioClass: 'iradio_square-blue',
		    increaseArea: '20%'
		  }).on('ifChanged', function(e) {
            scope.$apply(function() {
              update(e.target.checked);
            });
          });

          scope.$watch(attr.ngModel, function(model) {
            $(element).iCheck('update');
          }, true);
          
        })
      }
    }
  }])