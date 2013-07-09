$(document).ready(function(){

	
  $("li").click(function(){
        var lloc=$(this).find("a").attr("id");
   $.get("visordns2.php?domini="+$(this).text(),function(data,status){
	   
	$("#resultats").html(data);
	   $("#resultats").css("border","solid 2px #D3E2EA");
	   $("#resultats").hide();
	   $("#resultats").show(400);
$(lloc).focus();
    //alert("Data: " + data + "\nStatus: " + status);
	});
  });

}); //final de  $(document).ready(function(){
