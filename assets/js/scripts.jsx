import React from "react";
import ReactDOM from "react-dom/client";
import Search from "./Search.jsx";

document.addEventListener("DOMContentLoaded", function () {
  // document.getElementById("home-video").play();

  const searchContainer = jQuery("#search-container")[0];
  if (searchContainer)
  ReactDOM.createRoot(searchContainer).render(
    <React.StrictMode>
      <Search/>
    </React.StrictMode>
  );

});

