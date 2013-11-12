
<%@ page import="manager.Click" %>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="layout" content="kickstart" />
	<g:set var="entityName" value="${message(code: 'click.label', default: 'Click')}" />
	<title><g:message code="default.list.label" args="[entityName]" /></title>
</head>

<body>
	
<section id="list-click" class="first">

	<table class="table table-bordered">
		<thead>
			<tr>
			
				<g:sortableColumn property="apiKey" title="${message(code: 'click.apiKey.label', default: 'Api Key')}" />
			
				<g:sortableColumn property="url" title="${message(code: 'click.url.label', default: 'Url')}" />
			
			</tr>
		</thead>
		<tbody>
		<g:each in="${clickInstanceList}" status="i" var="clickInstance">
			<tr class="${(i % 2) == 0 ? 'odd' : 'even'}">
			
				<td><g:link action="show" id="${clickInstance.id}">${fieldValue(bean: clickInstance, field: "apiKey")}</g:link></td>
			
				<td>${fieldValue(bean: clickInstance, field: "url")}</td>
			
			</tr>
		</g:each>
		</tbody>
	</table>
	<div class="pagination">
		<bs:paginate total="${clickInstanceTotal}" />
	</div>
</section>

</body>

</html>
