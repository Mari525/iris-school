  <!--   window  -->
<script>
var swidth=$(window).width();
var sheight=$(window).height();
if ( (swidth>sheight)    )
    {
         $("div.nspArt a").click(function(){
            {
              return hs.htmlExpand(this, { outlineType: 'rounded-white', width: '830',  height: '530', wrapperClassName: 'draggable-header', objectType: 'ajax' }  ); }
              });
     }
else
     {
           $("div.nspArt a.title").click(function(){
            {
            return hs.htmlExpand(this, { outlineType: 'rounded-white', width: '250',  height: '530', wrapperClassName: 'draggable-header', objectType: 'ajax' }  ); }
             });
     }

</script>
 <!--   window  -->












