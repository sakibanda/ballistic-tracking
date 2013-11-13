<ul class="nav pull-right">
	<li class="dropdown dropdown-btn">
<%--<sec:ifNotLoggedIn>--%>

		<a class="dropdown-toggle" role="button" data-toggle="dropdown" data-target="#" href="#" tabindex="-1">
			<!-- TODO: integrate Springsource Security etc. and show User's name ... -->
    		<i class="icon-user"></i>
    		<g:message code="security.signin.label"/><b class="caret"></b>
		</a>



<%--</sec:ifNotLoggedIn>--%>
<%--<sec:ifLoggedIn>--%>

<%--		<a class="dropdown-toggle" role="button" data-toggle="dropdown" data-target="#" href="#">--%>
<%--			<!-- TODO: Only show menu items based on permissions (e.g., Guest has no account page) -->--%>
<%--			<i class="icon-user icon-large icon-white"></i>--%>
<%--			${user.name}--%>
<%--			<g:message code="default.user.unknown.label" default="Guest"/> <b class="caret"></b>--%>
<%--		</a>--%>
		<ul class="dropdown-menu" role="menu">
			<!-- TODO: Only show menu items based on permissions -->
			%{--<li class=""><a href="${createLink(uri: '/')}">--}%
				%{--<i class="icon-user"></i>--}%
				%{--<g:message code="user.show.label"/>--}%
			%{--</a></li>--}%
			%{--<li class=""><a href="${createLink(uri: '/')}">--}%
				%{--<i class="icon-cogs"></i>--}%
			%{--<g:message code="user.settings.change.label"/>--}%
			%{--</a></li>--}%

			<li class=""><a href="${createLink(uri: '/auth/signOut')}">
				<i class="icon-off"></i>
				<g:message code="security.signoff.label"/>

			</a></li>
		</ul>

<%--</sec:ifLoggedIn>--%>
	</li>
</ul>

<noscript>
<ul class="nav pull-right">
	<li class="">
		<g:link controller="user" action="show"><g:message code="default.user.unknown.label"/></g:link>
	</li>
</ul>
</noscript>
