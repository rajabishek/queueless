new Vue({
	el: 'body',
	data: {
		users: [],
		attendingUsers: [],
		pusher: null,
		channel: null,
		events: [
			'Queueless\\Events\\Users\\NewAppointmentWasRequested',
			'Queueless\\Events\\Employees\\NextAppointmentWasRequested'
		]
	},
    ready: function(){
    	
    	this.syncData();
    	
    	this.setupPusherInstance();
        
        this.setupPusherListeners();

    },
    methods: {
    	
    	setupPusherInstance: function(){
    		this.pusher = new Pusher('7c121081a67907b4bb30', { encrypted: true });

    		// Enable pusher logging - don't include this in production
			Pusher.log = function(message) {
				if (window.console && window.console.log) {
					window.console.log(message);
				}
			};
    	},

    	setupPusherListeners: function(){
    		
    		this.channel = this.pusher.subscribe('organisation.2');
		    
		    for(var i=0;i<this.events.length;++i){
		    	this.channel.bind(this.events[i], this.syncData);
		    }
    	},

    	syncData: function(){
			this.$http({url: '/admin/users/api/queue', method: 'GET'})
			.then(function (response) {
				this.users = response.data.users;
				this.attendingUsers = response.data.attendingUsers;
			}, function (response) {
				console.log(response);
			});
    	}
    }
});