require 'zendesk_api'
require 'json'
require_relative "../lib/client"

ticket = ZendeskAPI::Ticket.new(client, :comment => { :value => "attachments" })

#Using a path
ticket.comment.uploads << "img.jpg"

#Using the File class
#ticket.comment.uploads << File.new("img.jpg")

ticket.save
puts ticket.inspect
