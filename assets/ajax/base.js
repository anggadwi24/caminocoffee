

 var getUrl = window.location;
 var baseUrl = getUrl .protocol + "//" + getUrl.host+'/'+ getUrl.pathname.split('/')[1];;
    
  export default baseUrl;