Optimizing collection queries is impossible due to limit, offset and order
If those are not present collection can be optimized, but:
1) the check must be done during parsing, becuse subqueries are mutable
2) the modelName must be checked. To do this we either have to keep track of the relationship path in the mapper or somehow pass it to handler.
2.a)Keeping track of path would require sides to have a targetModelName() method to show next model in chain. Also there would have ot be a chainable() method or maybe just check for an interface.
2.b)We could pass the collection to a handler, but we'd need to know which handler. And we need 2.a for that.
3) embedded models don't have an id, so they cannot be used for collections. EmbeddedGroupMapper should throw exceptions whn it encounteres a collection.
Fuck it man. Let's just finish this!