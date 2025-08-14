(function(){
    document.addEventListener('DOMContentLoaded', function(){
        var search = document.querySelector('.filter-bar input[name="q"]');
        if(search){
            var debounce = function(fn, delay){
                var timer; return function(){ var ctx=this,args=arguments; clearTimeout(timer); timer=setTimeout(function(){fn.apply(ctx,args);},delay); };
            };
            search.addEventListener('input', debounce(function(){ this.form.submit(); },500));
        }
        var viewInput = document.querySelector('.filter-bar input[name="view"]');
        var viewButtons = document.querySelectorAll('[data-view-toggle]');
        if(viewInput){
            var stored = localStorage.getItem('reunioes_view');
            if(stored){ viewInput.value = stored; }
            viewButtons.forEach(function(btn){
                btn.addEventListener('click', function(e){
                    e.preventDefault();
                    var v = this.getAttribute('data-view-toggle');
                    viewInput.value = v;
                    localStorage.setItem('reunioes_view', v);
                    this.closest('form').submit();
                });
            });
        }
    });
})();
