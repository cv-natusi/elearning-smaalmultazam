$(document).ready(function () {
	pageUrl = pageUrl.slice(0, -1)

	PaginationInit(page, size, pageUrl);

	Array.from($('.pageInput')).forEach(element => {
		$(element).blur(
			function (event) {
				ToPage(this)
			}
		)
		$(element).keyup(
			function (event) {
				if (event.which === 13) { ToPage(this) }
			}
		)
	});
})

const ToPage = (elem) => {
	var p = parseInt(elem.value);

	if (!isNaN(parseFloat(p)) && isFinite(p)) {
		if (p > size) {
			p = size;
		} else if (p < 1) {
			p = 1;
		}
	} else {
		p = page;
		return
	}

	window.location.href = pageUrl + p
}
const PaginationInit = (page, size, pageUrl) => {
	var step = 2;
	var innerHTML = '<li><a href="' + prevUrl + '" id="prevUrl">Prev</a></li>'

	if (size < step) {
		innerHTML += Add(1, size + 1);
	} else if (page <= step) {
		innerHTML += Add(1, step + 1);
		innerHTML += Last(size);
	} else if (page > size - step) {
		innerHTML += First();
		innerHTML += Add(
			size + 1 - step,
			size + 1
		);
	} else {
		innerHTML += First();
		innerHTML += Add(
			page - step + 1,
			page + step
		);
		innerHTML += Last(size);
	}
	innerHTML += '<li><a href="' + nextUrl + '" id="nextUrl">Next</a></li>'
	Finish(innerHTML);
}

const First = () => {
	return '<li><a href="' + pageUrl + 1 + '">1</a></li><input type="text" class="pageInput">';
}

const Add = (s, f) => {
	var addItem = '';
	for (var i = s; i < f; i++) {
		addItem += '<li><a href="' + pageUrl + i + '">' + i + "</a></li>";
	}
	return addItem
}

const Last = (size) => {
	return '<input type="text" class="pageInput"><li><a href="' + pageUrl + size + '">' + size + "</a></li>";
}

const Finish = (innerHTML) => {
	$('.pagination-area').html(innerHTML);
	Bind();
}

const Bind = () => {
	var pageList = $('.pagination-area').children('li')
	console.log(pageList.length);
	Array.from(pageList).forEach(element => {
		if ($(element).children('a').html() == page) {
			$(element).children('a').addClass(' active')
		}
	});
	if (page >= size) {
		$('#nextUrl').prop('disabled', true)
	}
	if (page <= size) {
		$('#prevUrl').prop('disabled', true)
	}
}