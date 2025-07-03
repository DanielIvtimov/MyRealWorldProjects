import React, { useState } from 'react'
import { LuCopy, LuCheck, LuCode } from "react-icons/lu";
import ReactMarkdown from "react-markdown";
import remarkGfm from "remark-gfm";
import { Prism as SyntaxHighlighter } from "react-syntax-highlighter";
import { oneLight } from 'react-syntax-highlighter/dist/esm/styles/prism';
import "./styles/AIResponsePreview.css"
 
const AIResponsePreview = ({content}) => {

  if(!content) return null;

  return (
    <div className="ai-response-container">
      <div className="ai-response-markdown">
        <ReactMarkdown 
          remarkPlugins={[remarkGfm]}
          components={{
            code({node, className, children, ...props}){
              const match = /language-(\w+)/.exec(className || "");
              const language = match ? match[1] : "";
              const isLine = !className
              return !isLine ? (
                <CodeBlock 
                  code={String(children).replace(/\n$/, "")}
                  language={language}
                />
              ) : (
                <code className="ai-response-inline-code" {...props}>
                  {children}
                </code>
              );
            },
            p({ children }){
              return <p className="ai-response-paragraph">{children}</p>;
            },
            strong({ children }){
              return <strong>{children}</strong>;
            },
            em({ children }){
              return <em>{children}</em>;
            },
            ul({ children }){
              return <ul className="ai-response-list">{children}</ul>
            },
            ol({ children }){
              return <ol className="ai-response-ordered-list">{children}</ol>
            },
            li({ children }){
              return <li className="ai-response-list-item">{children}</li>
            },
            blockquote({ children }){
              return <blockquote className="ai-response-blockquote">{children}</blockquote>
            },
            h1({ children }){
              return <h1 className="ai-response-heading-one">{children}</h1>
            },
            h2({ children }){
              return <h2 className="ai-response-heading-two">{children}</h2>
            },
            h3({ children }){
              return <h3 className="ai-response-heading-three">{children}</h3>
            },
            h4({ children }){
              return <h4 className="ai-response-heading-four">{children}</h4>
            },
            a({ children }){
              return <a className="ai-response-link">{children}</a>
            },
            table({ children }){
              return (
                <div className="ai-response-table-wrapper">
                  <table className="ai-response-table">
                    {children}
                  </table>
                </div>
              );
            },
            thead({ children }){
              return <thead className="ai-response-table-head">{children}</thead>
            },
            tbody({ children }){
              return <tbody className="ai-response-table-body">{children}</tbody>
            },
            tr({ children }){
              return <tr>{children}</tr>
            },
            th({ children }){
              return <th className="ai-response-table-header-cell">{children}</th>
            },
            td({ children }){
              return <td className="ai-response-table-data-cell">{children}</td>
            },
            hr({ children }){
              return <hr className="ai-response-divider" />  
            },
            img({ src, alt}){
              return <img src={src} alt={alt} className="ai-response-image" />
            }
          }}
        >
          {content}
        </ReactMarkdown>
      </div>
    </div>
  )
}

function CodeBlock({code, language}) {
  const [copied, setCopied] = useState(false);

  const copyCode = () => {
    navigator.clipboard.writeText(code);
    setCopied(true);
    setTimeout(() => {
      setCopied(false);
    }, 2000)
  }
  return <div className="ai-response-codeblock">
    <div className="ai-response-codeblock-header">
      <div className="ai-response-codeblock-info">
        <LuCode size={16} className="ai-response-codeblock-icon" />
        <span className="ai-response-codeblock-language">
          {language || "Code"}
        </span>
      </div>
      <button
        onClick={copyCode}
        className="ai-response-codeblock-copy-btn"
        aria-label="Copy code"
      >
        {copied ? (
          <LuCheck size={16} className="ai-response-codeblock-copy-icon" />
        ) : (
          <LuCopy size={16} />
        )}
        {copied && (
          <span className="ai-response-codeblock-copied-text">
            Copied!
          </span>
        )}
      </button>
    </div>
    <SyntaxHighlighter 
      language={language}
      style={oneLight}
      customStyle={{fontSize:12.5, margin: 0, padding: "16px", background: "transparent"}}
    >
      {code}
    </SyntaxHighlighter>
  </div>
}

export default AIResponsePreview