require 'zendesk_api'
require 'json'
require_relative "../lib/client"

tickets = client.tickets.show_many(:ids => [150, 157, 159], :verb => :post)

tickets.each do |ticket|
  puts ticket.inspect
end