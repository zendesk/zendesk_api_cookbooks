require 'zendesk_api'
require 'json'
require_relative "../lib/client"

ZendeskAPI::Ticket.incremental_export(client, 1386021049).each do |ticket|
  puts ticket.inspect
end