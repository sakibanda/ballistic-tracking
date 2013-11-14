<div class="">
	<ul class="nav nav-tabs" data-role="listview" data-split-icon="gear" data-filter="true">
	
%{--		<g:each status="i" var="c" in="${grailsApplication.controllerClasses.sort { it.logicalPropertyName } }">
			<li class="controller${params.controller == c.logicalPropertyName ? " active" : ""}">
				<g:link controller="${c.logicalPropertyName}" action="index">
					<g:message code="${c.logicalPropertyName}.label" default="${c.logicalPropertyName.capitalize()}"/>
				</g:link>
			</li>
		</g:each>--}%

        <li class="controller${params.controller == "home" ? " active" : ""}">

            <g:link controller="home" action="index">
                <g:message code="home.label" default="Home"/>
            </g:link>

        </li>

        <li class="controller${params.controller == "customer" ? " active" : ""}">
            <shiro:isLoggedIn>
                <g:link controller="customer" action="index">
                    <g:message code="customer.label" default="Customer  "/>
                </g:link>
            </shiro:isLoggedIn>
        </li>

        <li class="controller${params.controller == "click" ? " active" : ""}">
            <shiro:isLoggedIn>
                <g:link controller="click" action="index">
                    <g:message code="click.label" default="Click"/>
                </g:link>
            </shiro:isLoggedIn>
        </li>
	</ul>
</div>
