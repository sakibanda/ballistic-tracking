%{--<html>--}%
%{--<head>--}%
  %{--<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />--}%
  %{--<meta name="layout" content="main" />--}%
  %{--<title>Login</title>--}%
%{--</head>--}%
%{--<body>--}%
  %{--<g:if test="${flash.message}">--}%
    %{--<div class="message">${flash.message}</div>--}%
  %{--</g:if>--}%
  %{--<g:form action="signIn">--}%
    %{--<input type="hidden" name="targetUri" value="${targetUri}" />--}%
    %{--<table>--}%
      %{--<tbody>--}%
        %{--<tr>--}%
          %{--<td>Username:</td>--}%
          %{--<td><input type="text" name="username" value="${username}" /></td>--}%
        %{--</tr>--}%
        %{--<tr>--}%
          %{--<td>Password:</td>--}%
          %{--<td><input type="password" name="password" value="" /></td>--}%
        %{--</tr>--}%
        %{--<tr>--}%
          %{--<td>Remember me?:</td>--}%
          %{--<td><g:checkBox name="rememberMe" value="${rememberMe}" /></td>--}%
        %{--</tr>--}%
        %{--<tr>--}%
          %{--<td />--}%
          %{--<td><input type="submit" value="Sign in" /></td>--}%
        %{--</tr>--}%
      %{--</tbody>--}%
    %{--</table>--}%
  %{--</g:form>--}%
%{--</body>--}%
%{--</html>--}%
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <title>Login</title>
    <%-- Manual switch for the skin can be found in /view/_menu/_config.gsp --%>
    <r:require modules="jquery"/>
    <r:require modules="bootstrap"/>
    <r:require modules="bootstrap_utils"/>
    <r:layoutResources />
</head>
<body>

<g:render template="/_menu/navbar"/>
<div class="container" style="padding-top: 58px">
    <div class="row">
        <div class="span4 offset4 well">
            <legend>Please Sign In</legend>
            <g:if test="${flash.message}">
                <div class="alert alert-error">${flash.message}</div>
            </g:if>
            <form action="signIn">
                <input type="text" id="username" class="span4" name="username" placeholder="Username" value="${username}" />
                <input type="password" id="password" class="span4" name="password" placeholder="Password">
                <label class="checkbox">
                    <g:checkBox name="rememberMe" value="${rememberMe}" /> Remember Me
                </label>
                <button type="submit" name="submit" class="btn btn-primary btn-block">Sign in</button>
            </form>
        </div>
    </div>
</div>
<g:render template="/layouts/footer"/>
<r:layoutResources />
</body>
</html>
