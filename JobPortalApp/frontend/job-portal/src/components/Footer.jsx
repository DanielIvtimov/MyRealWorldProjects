import React from 'react'

const Footer = () => {
  return (
    <footer className="border-t border-gray-200 py-8">
      <div className="container mx-auto px-4">
        <div className="flex flex-col md:flex-row justify-between items-center">
          <div className="mb-4 md:mb-0 text-center md:text-left">
            <h2 className="text-xl font-bold">Job Hunt</h2>
            <p className="text-sm text-gray-500">© 2024 Your Company, ALL rights reserved.</p>
          </div>

          <div className="flex space-x-4 mt-4 md:mt-0">
            <a href="https://facebook.com" className="text-gray-600 hover:text-gray-900" aria-label="Facebook">
              <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M22 12c0-5.522-4.477-10-10-10S2 6.478 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.988H7.898v-2.89h2.54V9.797c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.242 0-1.63.772-1.63 1.562v1.875h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
              </svg>
            </a>

            <a href="https://twitter.com" className="text-gray-600 hover:text-gray-900" aria-label="Twitter">
              <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
              </svg>
            </a>

            <a href="https://linkedin.com" className="text-gray-600 hover:text-gray-900" aria-label="LinkedIn">
              <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452H16.85v-5.569c0-1.3-.025-2.975-1.811-2.975-1.815 0-2.094 1.418-2.094 2.882v5.662H9.253V9h3.427v1.561h.049c.477-.9 1.637-1.848 3.368-1.848 3.601 0 4.268 2.37 4.268 5.455v6.284zM5.337 7.433a1.985 1.985 0 11-.001-3.97 1.985 1.985 0 01.001 3.97zM7.119 20.452H3.554V9H7.12v11.452z" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </footer>
  );
}

export default Footer