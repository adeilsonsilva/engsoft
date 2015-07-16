function swap_class(elementClass){
	if (elementClass === "fa fa-plus-square") {
		return "fa fa-minus-square";
	}else if (elementClass === "fa fa-minus-square") {
		return "fa fa-plus-square";
	}
}

function swap_display(elementDisplay){
	if (elementDisplay === "") {
		return "block";
	}else if (elementDisplay === "block") {
		return "none";
	}
}

function show_divs(){
	var elements = document.getElementsByClassName("fa fa-plus-square");
	for (var i = elements.length - 1; i >= 0; i--) {
		elements[i].addEventListener("click",
					function(){
						this.className = swap_class(this.className);
						this.querySelector('.doi-links').style.display = swap_display(this.querySelector('.doi-links').style.display);
					});
	};
}