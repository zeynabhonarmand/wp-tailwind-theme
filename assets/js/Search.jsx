import React, { useState, useEffect, useRef } from "react";
import axios from "axios";
import classNames from "classnames";
import { postTypes } from "./config";
const WAIT_BEFORE_SEARCH = 500; //in milliseconds

const Search = (props) => {
  const [showSearch, setShowSearch] = useState(false);
  const [query, setQuery] = useState("");
  const [searchDone, setSearchDone] = useState("");
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(false);
  const cancelTokenSource = axios.CancelToken.source();
  const inputElement = useRef();
  const lastKeyPressed = useRef(0);
  useEffect(()=>{
    if (inputElement.current)
      inputElement.current.focus();
  },[showSearch])

  const search = async (Q) => {
    if (!Q) {
      return setSearchDone(false);
    }

    if (Date.now() - lastKeyPressed.current < WAIT_BEFORE_SEARCH) return;
    setLoading(true);

    try {
      const response = await axios.get(
        `wp-json/part_institute/v1/search/?q=${Q}`,
        {
          cancelToken: cancelTokenSource.token,
        }
      );
      setResults(response.data);
      setSearchDone(true);
    } catch (error) {
      if (axios.isCancel(error)) {
        console.log("Request canceled:", error.message);
      } else {
        console.error("Error fetching data:", error);
      }
    } finally {
      setLoading(false);
    }
  };

  const handleInputChange = (event) => {
    setQuery(event.target.value);
    lastKeyPressed.current = Date.now();

    setTimeout(() => search(event.target.value), WAIT_BEFORE_SEARCH + 10);
  };

  const filterResults = (type = null) => {
    if (!type) return results;
    return results.filter((item) => item.post_type == type);
  };
  if (!showSearch)
    return (
      <div className="search-icon-closed" onClick={(e) => setShowSearch(true)}>
        Search
      </div>
    );

  return (
    <>
      <div
        className="search-back-drop fixed top-0 right-0 bottom-0 left-0 z-8 w-screen h-screen"
        onClick={() => setShowSearch(false)}
      ></div>
      <div className="search-container">
        <div className={"search-input-wrapper z-10"}>
          <input
            ref={inputElement}
            autoComplete="none"
            id="search-input"
            value={query}
            placeholder="مقاله، ویدیو، پادکست، یا موضوع مورد نظر خود را جستجو کنید"
            onChange={handleInputChange}
          />
          <button
            className={classNames("close-icon z-10", {
              loading: loading,
              close: showSearch,
            })}
            onClick={() => {
              setShowSearch(false);
            }}
          >
            Search
          </button>
        </div>

        {results.length > 0 && (
          <>
            <div className="search-results z-10 top-12 p-6 grid md:grid-cols-5 grid-cols-1">
              {postTypes.map((postType) => (
                <div className="border-gray-700 px-2">
                  <h2 className="text-gray-500 fs-16 fw-700">{postType.title}</h2>
                  <ul className="pt-3">
                    {filterResults(postType.type).map((result) => (
                      <li key={result.id}>
                        <a className="fs-14 fw-400" href={result.permalink}>
                          {result.title}
                        </a>
                      </li>
                    ))}
                  </ul>
                  {filterResults(postType.type).length == 0 && (
                    <p className="text-center fs-14 fw-400"> نتیجه ای یافت نشد</p>
                  )}
                </div>
              ))}
            </div>
          </>
        )}
        {results.length == 0 && (
          <>
            {" "}
            <div className="search-results fs-14 fw-400 top-12 p-6 text-center">
              هیچ نتیجه ای یافت نشد
            </div>
          </>
        )}
      </div>
    </>
  );
};

export default Search;
