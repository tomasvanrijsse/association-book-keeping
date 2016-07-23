$(document).ready(function(){
    var suggestions = [];
    
    $('.row.suggestion').each(function(){
        suggestions.push(
        new Suggestion($(this))
        );
    });
});

function Suggestion($el){
    var self = this;
    
    self.$el = $el;
    self.credit = new Array();
    self.debet = new Array();
    self.id = this.$el.data('id');
    
    $el.find('.table.credit a').each(function(){
        self.credit.push(new Button($(this),'credit',self));
    })
    $el.find('.table.debet a').each(function(){
        self.debet.push(new Button($(this),'debet',self));
    });
    
    $el.find('a.hidelink').click($.proxy(this.hide,this));
    $el.find('button').click($.proxy(this.save,this));
}
Suggestion.prototype = {
    listen: function (){
        var debeton, crediton = debeton = false;
        $.each(this.credit,function(){
            if(this.checked) crediton = true;
        });
        $.each(this.debet,function(){
            if(this.checked) debeton = true;
        });
        if(crediton && debeton){
            this.$el.find('button').fadeTo(400,1);
            this.$el.find('button').click($.proxy(this.save,this));
        } else {
            this.$el.find('button').fadeTo(400,0.5);
            this.$el.find('button').unbind('click');
        }
    },
    deactivateButtons : function (type){
        $.each(this[type],function(){
            this.deactivate();
        })
    },
    hide : function(){
        $.post('/transacties/hideSuggestion',{id:this.id});
        this.$el.slideUp()
            .prev('hr').slideUp()
            .prev('hr').slideUp();
        return false;
    },
    save : function(){
        if(window.location.pathname.indexOf('verwerkt')>0){
            if(!confirm('Als u deze wijziging opslaat dan zullen betreffende boekingen ongedaan gemaakt worden')){
                return false;
            }
        }
        var data = {suggestie_id:this.id};
        $.each(this.credit,function(){
            if(this.checked) data.credit_id = this.id;
        });
        $.each(this.debet,function(){
            if(this.checked) data.debet_id = this.id;
        });
        $.post('/transacties/saveSuggestion',data);
        if($('.nav-pills li.active').data('status')!=2){
            this.$el.slideUp(function(){
                $('.nav-pills li:nth-child(2) a').animate({
                    backgroundColor: "#0088cc",
                    color:'#FFF'
                }, 500 ).animate({
                    backgroundColor: "#FFF",
                    color:'#0088cc'
                }, 500 );
            }).prev('hr').slideUp().prev('hr').slideUp();
        } else {
            this.$el.fadeTo(500,0.4).fadeTo(500,1);
        }
        return false;
    }
}

function Button($el,type,parent){
    this.$el = $el;
    this.type = type;
    this.id = $el.parents('li').data('id');
    this.$li = $el.parents('li');
    this.parent = parent;
    this.checked = false;
    $el.click($.proxy(this.onclick,this));
}

Button.prototype = {
    activate : function(){
        this.$li.addClass('strike');
        this.$el.addClass('btn-inverse');
        this.$el.find('i').addClass('icon-white');
        this.checked = true;
    },
    deactivate: function(){
        this.$li.removeClass('strike');
        this.$el.removeClass('btn-inverse');
        this.$el.find('i').removeClass('icon-white');
        this.checked = false;
    },
    onclick : function(){
        if(this.$el.hasClass('disabled')){
            return false;
        }
        if(this.checked){
            this.deactivate();
        } else {
            this.parent.deactivateButtons(this.type);
            this.activate();
        }
        this.parent.listen();
        return false;
    }
}