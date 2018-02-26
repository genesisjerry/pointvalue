var multi= angular.module('pointV',['ui.router'])
var myHandle="http://localhost/fdev/pointVmlm/php/action.php";
var config = {header:{'Content_Type':'application/x-www-form-urlencoded'}};

//////Factory Call////////
multi.factory('pointValue', function($http){
  factoryobj = {
    solve:function(myHandle,data){
    return $http.post(myHandle,data).then(function(response){
	alert (JSON.stringify(response));
	return response
	})
    }
  }  
return factoryobj   
})

multi.config(function($stateProvider,$urlRouterProvider){
$urlRouterProvider.otherwise('/');  
$stateProvider.state('index',{url:'/',views:{'':{templateUrl:'partials/reg.html',controller:'valreg',
resolve:{
	quarrel:function($q,pointValue){
		var judge = $q.defer();
		var jury = {pusha:'3'};
		pointValue.solve(myHandle,jury).then(function(response){
			if(response.data.details.code == "00"){
				judge.resolve(response);
			}else{
				alert(JSON.stringify(response.data.details.message));
				judge.resolve(response);
			}
		},function(error){
			console.log(error);
		});
		return judge.promise;
	}
}},},})

.state('bank',{url:'/bank_details',views:{'':{templateUrl:'partials/bank.html',controller:'bnkup'},},})
.state('payment',{url:'/payment',views:{'':{templateUrl:'partials/payment.html',controller:'payM'},},})
.state('dash.requestwithdrawals',{url:'/requestwithdrawals',views:{'main':{templateUrl:'partials/requestwithdrawals.html',controller:'withrqst'},},})
.state('dash.food-wallet',{url:'/food-wallet',views:{'main':{templateUrl:'partials/food-wallet.html',controller:'food-wallet',resolve:{
	issue:function($q,pointValue){
		var judge = $q.defer();
		var jury = {pusha:'25'};
		pointValue. solve(myHandle,jury).then(function(response){
		alert(JSON.stringify(response))
			if(response.data.details.code == '00'){
			judge.resolve(response);
			}else{judge.resolve(response);}
			},function(failure){
				})
	return judge.promise;
	}
}},},})

.state('dash.e-wallet',{url:'/e-wallet',views:{'main':{templateUrl:'partials/e-wallet.html',controller:'e-wallet',resolve:{
	issue:function($q,pointValue){
		var judge = $q.defer();
		var jury = {pusha:'24'};
		pointValue. solve(myHandle,jury).then(function(response){
		alert(JSON.stringify(response))
			if(response.data.details.code == '00'){
			judge.resolve(response);
			}else{judge.resolve(response);}
			},function(failure){
				})
	return judge.promise;
	}
}},},})

.state('dash.network',{url:'/network',views:{'main':{templateUrl:'partials/network.html',controller:'drctdown',resolve:{
	issue:function($q,pointValue){
		var judge = $q.defer();
		var jury = {pusha:'22'};
		pointValue. solve(myHandle,jury).then(function(response){
		alert(JSON.stringify(response))
			if(response.data.details.code == '00'){
			judge.resolve(response);
			}else{judge.resolve(response);}
			},function(failure){
				})
	return judge.promise;
	}
}},},})

.state('login',{url:'/login',views:{'':{templateUrl:'partials/login.html',controller:'logme'},},})
.state('dash.subuser',{url:'/subuser',views:{'main':{templateUrl:'partials/subuser.html',controller:'crtsubuser'},},})
.state('dash.viewsubuser',{url:'/viewsubuser',views:{'main':{templateUrl:'partials/viewsubuser.html',controller:'vwsubuser',resolve:{
	issue:function($q,pointValue){
		var judge = $q.defer();
		var jury = {pusha:'20'};
		pointValue. solve(myHandle,jury).then(function(response){
		alert(JSON.stringify(response))
			if(response.data.details.code == '00'){
			judge.resolve(response);
			}else{judge.resolve(response);}
			},function(failure){
				})
	return judge.promise;
	}
}},},})
.state('dash.messaging',{url:'/messaging',views:{'main':{templateUrl:'partials/messaging.html',controller:'myinbox',resolve:{
	issue:function($q,pointValue){
		var judge = $q.defer();
		var jury = {pusha:'13'};
		pointValue. solve(myHandle,jury).then(function(response){
		alert(JSON.stringify(response))
			if(response.data.details.code == '00'){
			judge.resolve(response);
			}else{judge.resolve(response);}
			},function(failure){
				})
	return judge.promise;
	}
}},},})
	
.state('dash.contactadmin',{url:'/contactadmin',views:{'main':{templateUrl:'partials/contactadmin.html',controller:'contactadmin'},},})
.state('dash.userreply',{url:'/userreply',views:{'main':{templateUrl:'partials/userreply.html',controller:'replyadmin',resolve:{
	issue:function($q,pointValue){
		var judge = $q.defer();
		var jury = {pusha:'17'};
		pointValue. solve(myHandle,jury).then(function(response){
		alert(JSON.stringify(response))
			if(response.data.details.code == '00'){
			judge.resolve(response);
			}else{judge.resolve(response);}
			},function(failure){
				})
	return judge.promise;
	}
}},},})

.state('dash.sent',{url:'/sent',views:{'main':{templateUrl:'partials/sent.html',controller:'mysent',resolve:{
	issue:function($q,pointValue){
		var judge = $q.defer();
		var jury = {pusha:'14'};
		pointValue. solve(myHandle,jury).then(function(response){
		alert(JSON.stringify(response))
			if(response.data.details.code == '00'){
			judge.resolve(response);
			}else{judge.resolve(response);}
			},function(failure){
				})
	return judge.promise;
	}
}},},})

.state('dash.viewPrdts',{url:'/viewPrdts',views:{'main':{templateUrl:'partials/viewPrdts.html',controller:'vprdts',resolve:{
	issue:function($q,pointValue){
		var judge = $q.defer();
		var jury = {pusha:'ad10'};
		pointValue. solve(myHandle,jury).then(function(response){
		alert(JSON.stringify(response))
			if(response.data.details.code == '00'){
			judge.resolve(response);
			}else{judge.resolve(response);}
			},function(failure){
				})
	return judge.promise;
	}
	}},},})

.state('dash.myaccount',{url:'/myaccount',views:{'main':{templateUrl:'partials/myaccount.html',controller:'myacct',
resolve:{
	quarrel:function($q,pointValue){
		var judge = $q.defer();
		var jury = {pusha:'3'};
		pointValue.solve(myHandle,jury).then(function(response){
			if(response.data.details.code == "00"){
				judge.resolve(response);
			}else{
				alert(JSON.stringify(response.data.details.message));
				judge.resolve(response);
			}
		},function(error){
			console.log(error);
		});
		return judge.promise;
	}
}},},})

.state('dash',{url:'/BackOffice',views:{'':{templateUrl:'partials/dash.html',controller:'profile',
resolve:{
	quarrel:function($q,pointValue){
		var judge = $q.defer();
		var jury = {pusha:'7'};
		pointValue.solve(myHandle,jury).then(function(response){
			if(response.data.details.code == "00"){
				judge.resolve(response);
			}else{
				alert(JSON.stringify(response.data.details.message));
				judge.resolve(response);
			}
		},function(error){
			console.log(error);
		});
		return judge.promise;
	}
}},},})
});

////////Registration Controller///////////////
multi.controller('valreg',function($scope,pointValue,$state,quarrel){
 if(quarrel.data.details.code == '00'){
	$scope.mycountrys = quarrel.data.details.message;
	}else {$scope.nocountry = "No Data Found";}
	
$scope.selcount=function(){ alert($scope.selectedItem)
       	$scope.val =$scope.selectedItem
		var data =$scope.val 
		$scope.formData = {pusha:'4'}
        $scope.formData.dat=data;
        var datas = $scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
        alert(JSON.stringify(response));
        if(response.data.details.code == '00'){
		$scope.states= response.data.details.message;
        }else{$scope.msg="something went wrong";}
        })
}

		$scope.selstate=function(){ alert($scope.selectedItem1)
       	$scope.val =$scope.selectedItem1
		var data =$scope.val 
		$scope.formData = {pusha:'5'}
        $scope.formData.dat=data;
        var datas = $scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
        alert(JSON.stringify(response));
        if(response.data.details.code == '00'){
		$scope.lgas= response.data.details.message;
        }else{$scope.msg="something went wrong";}
        })
}
		$scope.sellga=function(){ alert($scope.selectedItem2)
       	$scope.val =$scope.selectedItem2
		var data =$scope.val 
		$scope.formData = {pusha:'6'}
        $scope.formData.dat=data;
        var datas = $scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
        alert(JSON.stringify(response));
        if(response.data.details.code == '00'){
		$scope.lgas= response.data.details.message;
        }else{$scope.msg="something went wrong";}
        })
}

		$scope.formData1 = {pusha:'1'};
		$scope.submitForm = function(){
		var datas = $scope.formData1;
		pointValue.solve(myHandle,datas).then(function(response){
		alert(JSON.stringify(response))	
		if(response.data.details.code =='00'){	
		$state.go("bank");
		}else {$scope.er_msg= "something went wrong"}
		});
	}
});

multi.controller('vprdts',function($scope,pointValue,issue,$state){
		if(issue.data.details.code == '00'){
		$scope.prdcts = issue.data.details.message;
		alert('issue: '+JSON.stringify(issue));
		}else {$scope.nosus= "No Avalaible Products";}
});

multi.controller('e-wallet',function($scope,pointValue,issue,$state){
		if(issue.data.details.code == '00'){
		$scope.balance = issue.data.details.message[0].avCash;
		alert('issue: '+JSON.stringify(issue));
		}else {$scope.nosus= "No Avalaible Products";}
});

multi.controller('food-wallet',function($scope,pointValue,issue,$state){
		if(issue.data.details.code == '00'){
		$scope.balance = issue.data.details.message[0].avCash;
		alert('issue: '+JSON.stringify(issue));
		}else {$scope.nosus= "No Avalaible Products";}
});

multi.controller('vwsubuser',function($scope,pointValue,issue,$state){
		if(issue.data.details.code == '00'){
		$scope.vsubs = issue.data.details.message;
		alert('issue: '+JSON.stringify(issue));
		}else {$scope.nosub= "No Subuser Created";}
		
		$scope.paysub =function(o){$scope.formData = {pusha:'21'};
		$scope.formData.user = o;
		var datas =$scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
		alert(JSON.stringify(response));
		if(response.data.details.code == '00'){
		$scope.paymnt="Payment Succesful";	
		$state.reload();
		}else{$scope.aduser="Payment Already Made";}
		})
	}	
});

/////////////// CONTROLLER THAT HANDLES DIRECT REFERALS/////////////////////
multi.controller('drctdown',function($scope,pointValue,issue,$state){
		if(issue.data.details.code == '00'){
		$scope.referals = issue.data.details.message;
		alert('issue: '+JSON.stringify(issue));
		}else {$scope.noreferal= "You Have No Referal Yet";}
});


///////Bank Details Update Controller////////////
multi.controller('bnkup',function($scope,pointValue,$state){
$scope.formData = {pusha:'2'};
	$scope.submitForm = function(){
		var datas = $scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
		alert(JSON.stringify(response))	
			if(response.data.details.code =='00'){	
				$state.go("payment");
			}else {$scope.er_msg= "something went wrong"}
		});
	}
});

///////Login Controller///////////////
multi.controller('logme',function($scope,pointValue,$state){
$scope.formData = {pusha:'8'};
	$scope.submitForm = function(){
		var datas = $scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
		alert(JSON.stringify(response))	
			if(response.data.details.code =='00'){	
				$state.go("dash");
			}else {$scope.er_msg= "something went wrong"}
		});
	}
});

////////////////USER DASHBOARD CONTROLLER/////////////////////
multi.controller('profile',function($scope,pointValue,$state,quarrel){
 if(quarrel.data.details.code == '00'){
	$scope.profiles = quarrel.data.details.message[0];
	}else {$scope.nocountry = "No Data Found";}
	
		$scope.goto=function(){ 
		$scope.formData = {pusha:'9'}
        var datas = $scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
        alert(JSON.stringify(response));
        if(response.data.details.code == '03'){
			$state.go("login");
        }else{$scope.msg="something went wrong";}
        })
	}
});

////////////////USER EDIT PROFILE CONTROLLER/////////////////////
multi.controller('myacct',function($scope,pointValue,$state,quarrel){
 if(quarrel.data.details.code == '00'){
	$scope.mycountrys = quarrel.data.details.message;
	}else {$scope.nocountry = "No Data Found";}
	
		$scope.selcount=function(){ alert($scope.selectedItem)
       	$scope.val =$scope.selectedItem
		var data =$scope.val 
		$scope.formData = {pusha:'4'}
        $scope.formData.dat=data;
        var datas = $scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
        alert(JSON.stringify(response));
        if(response.data.details.code == '00'){
		$scope.states= response.data.details.message;
        }else{$scope.msg="something went wrong";}
        })
}

		$scope.selstate=function(){ alert($scope.selectedItem1)
       	$scope.val =$scope.selectedItem1
		var data =$scope.val 
		$scope.formData = {pusha:'5'}
        $scope.formData.dat=data;
        var datas = $scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
        alert(JSON.stringify(response));
        if(response.data.details.code == '00'){
		$scope.lgas= response.data.details.message;
        }else{$scope.msg="something went wrong";}
        })
}
		$scope.sellga=function(){ alert($scope.selectedItem2)
       	$scope.val =$scope.selectedItem2
		var data =$scope.val 
		$scope.formData = {pusha:'6'}
        $scope.formData.dat=data;
        var datas = $scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
        alert(JSON.stringify(response));
        if(response.data.details.code == '00'){
		$scope.lgas= response.data.details.message;
        }else{$scope.msg="something went wrong";}
        })
}

		$scope.formData1 = {pusha:'10'};
		$scope.submitForm = function(){
		var datas = $scope.formData1;
		pointValue.solve(myHandle,datas).then(function(response){
		alert(JSON.stringify(response))	
		if(response.data.details.code =='00'){	
		$scope.smsg = "Information Successfully Updated"
		}else {$scope.upwrng= "something went wrong"}
		});
	}
	
		$scope.formData2 = {pusha:'11'};
		$scope.resetPswd= function(){
		var datas = $scope.formData2;
		pointValue.solve(myHandle,datas).then(function(response){
		alert(JSON.stringify(response))	
		if(response.data.details.code =='00'){	
		$scope.psrst = "Password Reset Successful"
		}else {$scope.psrstwrng= "something went wrong"}
		});
	}
		$scope.formData9 = {pusha:'7'};
		var datas = $scope.formData9;
		pointValue.solve(myHandle,datas).then(function(response){
		alert(JSON.stringify(response))	
		if(response.data.details.code =='00'){
		$scope.profiles= response.data.details.message[0];
		}else {$scope.er_msg= "something went wrong"}
		});
});

////////////////USER COMPOSE MESSAGE CONTROLLER/////////////////////
multi.controller('contactadmin',function($scope,pointValue,$state){
$scope.formData = {pusha:'12'};
	$scope.submitForm = function(){
		var datas = $scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
		alert(JSON.stringify(response))	
			if(response.data.details.code =='00'){	
				$scope.msg ="Message Sent";
			}else {$scope.er_msg= "something went wrong"}
		});
	}
});

////////////////USER INBOX MESSAGE CONTROLLER/////////////////////
multi.controller('myinbox',function($scope,pointValue,issue,$state){
		if(issue.data.details.code == '00'){
		$scope.adminmsgs = issue.data.details.message;
		alert('issue: '+JSON.stringify(issue));
		}else {$scope.nomsg= "No Avalaible Message";}
		
		$scope.deletemsg =function(o){$scope.formData = {pusha:'15'};
		$scope.formData.code = o;
		var datas =$scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
		alert(JSON.stringify(response));
		if(response.data.details.code == '00'){
		$scope.duser="message Deleted";	
		$state.reload();
		}else{$scope.aduser="Message Is Already Deleted";}
		})
	}
	
		$scope.repmsg =function(o){
		$scope.formData = {pusha:'16'};
		$scope.formData.code = o;
		var datas =$scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
		alert(JSON.stringify(response));
		if(response.data.details.code == '00'){
			$state.go ('dash.userreply');
		}else{$scope.aduser="Something Went Wrong";}
		})
	}
});

///////////MESSAGE REPLY CONTROLLER////////////////////
multi.controller('replyadmin',function($scope,pointValue,issue,$state){
		if(issue.data.details.code == '00'){
		$scope.sender = issue.data.details.message[0];
		alert('issue: '+JSON.stringify(issue));
		}else {$scope.nomsg= "No Avalaible Message";}
		
		
	$scope.formData = {pusha:'18'};
	$scope.formData.sender = $scope.sender;
    alert($scope.formData);
	$scope.submitForm = function(){
		var datas = $scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
		alert(JSON.stringify(response))	
			if(response.data.details.code =='00'){	
				$scope.msg ="Message Sent";
			}else {$scope.er_msg= "something went wrong"}
		});
	}
});	 
	
////////////////USER SENT MESSAGE CONTROLLER/////////////////////
multi.controller('mysent',function($scope,pointValue,issue,$state){
		if(issue.data.details.code == '00'){
		$scope.sents = issue.data.details.message;
		alert('issue: '+JSON.stringify(issue));
		}else {$scope.nomsg= "No Avalaible Message";}
		
		$scope.deletesent =function(o){$scope.formData = {pusha:'15'};
		$scope.formData.code = o;
		var datas =$scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
		alert(JSON.stringify(response));
		if(response.data.details.code == '00'){
		$scope.duser="message Deleted";	
		$state.reload();
		}else{$scope.aduser="Message Is Already Deleted";}
		})
	}	
});

multi.controller('crtsubuser',function($scope,pointValue,$state){
$scope.formData = {pusha:'19'};
	$scope.submitForm = function(){
		var datas = $scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
		alert(JSON.stringify(response))	
			if(response.data.details.code =='00'){	
				$scope.msg ="Sub Accountb Successfully Created";
			}else {$scope.er_msg= "something went wrong"}
		});
	}
});

multi.controller('payM',function($scope,pointValue,$state){
$scope.formData = {pusha:'23'};
	$scope.submitForm = function(){
		var datas = $scope.formData;
		pointValue.solve(myHandle,datas).then(function(response){
		alert(JSON.stringify(response))	
			if(response.data.details.code =='00'){	
				$state.go("dash");
			}else {$scope.er_msg= "something went wrong"}
		});
	}
});

multi.controller('show',function($scope){	$scope.slt2 = false;$scope.pop = "fa fa-angle-right"
	$scope.sltclick2=function(){
	if($scope.slt2 == false){$scope.pop = "fa fa-angle-up"
	$scope.slt2 = true}else 
	if($scope.slt2 == true){$scope.pop = "fa fa-angle-right"
	$scope.slt2 = false}
	}
})

multi.controller('shows',function($scope){	$scope.slt3 = false;$scope.popy = "fa fa-angle-right"
	$scope.sltclick3=function(){
	if($scope.slt3 == false){$scope.popy = "fa fa-angle-up"
	$scope.slt3 = true}else 
	if($scope.slt3 == true){$scope.popy = "fa fa-angle-right"
	$scope.slt3 = false}
	}
})

multi.controller('showup',function($scope){	$scope.slt4 = false;$scope.popup = "fa fa-angle-right"
	$scope.sltclick4=function(){
	if($scope.slt4 == false){$scope.popup = "fa fa-angle-up"
	$scope.slt4 = true}else 
	if($scope.slt4 == true){$scope.popup = "fa fa-angle-right"
	$scope.slt4 = false}
	}
})

multi.controller('showdown',function($scope){	$scope.slt5 = false;$scope.popdwon = "fa fa-angle-right"
	$scope.sltclick5=function(){
	if($scope.slt5 == false){$scope.popdwon = "fa fa-angle-up"
	$scope.slt5 = true}else 
	if($scope.slt5 == true){$scope.popdwon = "fa fa-angle-right"
	$scope.slt5 = false}
	}
})

multi.controller('showadd',function($scope){	$scope.slt6 = false;$scope.popadd = "fa fa-angle-right"
	$scope.sltclick6=function(){
	if($scope.slt6 == false){$scope.popadd = "fa fa-angle-up"
	$scope.slt6 = true}else 
	if($scope.slt6 == true){$scope.popadd = "fa fa-angle-right"
	$scope.slt6 = false}
	}
})

multi.controller('showbox',function($scope){	$scope.slt7 = false;$scope.popbox = "fa fa-angle-right"
	$scope.sltclick7=function(){
	if($scope.slt7 == false){$scope.popbox = "fa fa-angle-up"
	$scope.slt7 = true}else 
	if($scope.slt7 == true){$scope.popbox = "fa fa-angle-right"
	$scope.slt7 = false}
	}
})
multi.controller('showrem',function($scope){	$scope.slt8 = false;$scope.poprem = "fa fa-angle-right"
	$scope.sltclick8=function(){
	if($scope.slt8 == false){$scope.poprem = "fa fa-angle-up"
	$scope.slt8 = true}else 
	if($scope.slt8 == true){$scope.poprem = "fa fa-angle-right"
	$scope.slt8 = false}
	}
})

multi.controller('showcon',function($scope){	$scope.slt9 = false;$scope.popcon = "fa fa-angle-right"
	$scope.sltclick9=function(){
	if($scope.slt9 == false){$scope.popcon= "fa fa-angle-up"
	$scope.slt9 = true}else 
	if($scope.slt9 == true){$scope.popcon= "fa fa-angle-right"
	$scope.slt9 = false}
	}
})

multi.controller('showsubu',function($scope){	$scope.slt10 = false;$scope.popsubu = "fa fa-angle-right"
	$scope.sltclick10=function(){
	if($scope.slt10 == false){$scope.popsubu= "fa fa-angle-up"
	$scope.slt10 = true}else 
	if($scope.slt10 == true){$scope.popsubu= "fa fa-angle-right"
	$scope.slt10 = false}
	}
})

