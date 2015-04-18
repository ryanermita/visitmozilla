define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/index.html',
    'persona'
], function($, _, Backbone, indexTemplate, Persona){
  var indexView = Backbone.View.extend({
    el: $('.row'),
    
    render: function(){
      compiledTemplate = _.template(indexTemplate);
      var data = {};
      this.$el.append(compiledTemplate, data);
    },

    events:{
      'click #login-button': 'login',
      'click #register-button': 'register'
    },

    // Use Callback Api of Mozilla Persona.
    login: function(e){
      navigator.id.get(this.verifyAssertion);
      console.log('login button clicked!');
    },

    verifyAssertion: function(assertion){

      // remove hashtag on the current url
      var baseUrl = window.location.href.replace(/#/g, '');

      $.ajax({
        url: baseUrl + "functions/login.php",
        method: "POST",
        data: { assertion: assertion },
        success: function(resp){
          if(resp.success){
            console.log('verify user success resp', resp);
          } else {
            console.log('verify user failed resp', resp);
          }
        }
      });

      console.log('assertion');
    },

    register: function(){
      console.log('register me ohhh');
    }

    
  });

  return indexView;
});
